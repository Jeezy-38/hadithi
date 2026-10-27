<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Dua;
use App\Models\Hadith;
use App\Models\PageView;
use App\Models\Payment;
use App\Models\QuranAyah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    private const TABS = ['overview', 'users', 'payments', 'content', 'system'];

    public function index(Request $request)
    {
        $tab = $request->get('tab');
        $activeTab = in_array($tab, self::TABS, true) ? $tab : 'overview';

        // Namba za vichwa vya tabs (nafuu) + data ya tab inayotumika TU
        $data = [
            'activeTab' => $activeTab,
            'totalUsers' => User::count(),
            'totalHadith' => Hadith::count(),
            'paymentsCompletedCount' => Payment::where('status', 'completed')->count(),
        ];

        $data += match ($activeTab) {
            'users' => $this->usersData($request),
            'payments' => $this->paymentsData($request),
            'content' => $this->contentData($request),
            'system' => $this->systemData(),
            default => $this->overviewData(),
        };

        return view('admin.dashboard', $data);
    }

    /**
     * Wageni wa kipekee kwa "mgeni-siku": ip_hash hubadilika kila siku (faragha),
     * kwa hiyo jumla ya siku nyingi ni jumla ya wageni wa kila siku, si watu tofauti.
     */
    private function uniqueVisitorDays(string $from): int
    {
        $distinct = PageView::query()
            ->select('viewed_date', 'ip_hash')
            ->where('viewed_date', '>=', $from)
            ->whereNotNull('ip_hash')
            ->distinct();

        return DB::query()->fromSub($distinct, 't')->count();
    }

    private function overviewData(): array
    {
        $today = Carbon::today()->toDateString();
        $sevenDaysAgo = Carbon::today()->subDays(6)->toDateString();
        $thirtyDaysAgo = Carbon::today()->subDays(29)->toDateString();

        $rawDailyViews = PageView::select('viewed_date', DB::raw('count(*) as total'))
            ->where('viewed_date', '>=', $sevenDaysAgo)
            ->groupBy('viewed_date')
            ->pluck('total', 'viewed_date')
            ->toArray();

        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $dailyStats[] = [
                'date' => $date,
                'label' => Carbon::today()->subDays($i)->isoFormat('ddd D'),
                'count' => $rawDailyViews[$date] ?? 0,
            ];
        }

        $views7Days = array_sum(array_column($dailyStats, 'count'));
        $viewsToday = $rawDailyViews[$today] ?? 0;

        return [
            'viewsToday' => $viewsToday,
            'uniqueToday' => PageView::where('viewed_date', $today)->distinct('ip_hash')->count('ip_hash'),
            'views7Days' => $views7Days,
            'unique7Days' => $this->uniqueVisitorDays($sevenDaysAgo),
            'dailyStats' => $dailyStats,
            'topPages' => PageView::select('path', DB::raw('count(*) as total'))
                ->where('viewed_date', '>=', $thirtyDaysAgo)
                ->groupBy('path')
                ->orderByDesc('total')
                ->limit(7)
                ->get(),
            'recentViews' => PageView::with('user')->latest('id')->limit(12)->get(),
            'totalAdmins' => User::where('is_admin', true)->count(),
            'totalSupporters' => User::where('is_supporter', true)->count(),
            'totalTzs' => Payment::where('status', 'completed')->where('currency', 'TZS')->sum('amount'),
            'totalUsd' => Payment::where('status', 'completed')->where('currency', 'USD')->sum('amount'),
            'monthTzs' => Payment::where('status', 'completed')
                ->where('currency', 'TZS')
                ->where('completed_at', '>=', Carbon::now()->startOfMonth())
                ->sum('amount'),
        ];
    }

    private function usersData(Request $request): array
    {
        $userRole = $request->get('user_role', 'all');
        $userSearch = trim((string) $request->get('user_search', ''));

        $usersQuery = User::withCount(['bookmarks', 'payments'])
            ->with('socialAccounts')
            ->latest('id');

        if ($userRole === 'admin') {
            $usersQuery->where('is_admin', true);
        } elseif ($userRole === 'supporter') {
            $usersQuery->where('is_supporter', true);
        } elseif ($userRole === 'regular') {
            $usersQuery->where('is_admin', false)->where('is_supporter', false);
        }

        if ($userSearch !== '') {
            $like = '%' . $this->escapeLike($userSearch) . '%';
            $usersQuery->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)->orWhere('email', 'like', $like);
            });
        }

        return [
            'totalAdmins' => User::where('is_admin', true)->count(),
            'totalSupporters' => User::where('is_supporter', true)->count(),
            'userRole' => $userRole,
            'userSearch' => $userSearch,
            'users' => $usersQuery->paginate(12, ['*'], 'users_page')->withQueryString(),
        ];
    }

    private function paymentsData(Request $request): array
    {
        $paymentStatus = $request->get('payment_status', 'all');
        $paymentSearch = trim((string) $request->get('payment_search', ''));

        $paymentsQuery = Payment::query()->latest();

        if (in_array($paymentStatus, ['completed', 'pending', 'failed'], true)) {
            $paymentsQuery->where('status', $paymentStatus);
        } else {
            $paymentStatus = 'all';
        }

        if ($paymentSearch !== '') {
            $like = '%' . $this->escapeLike($paymentSearch) . '%';
            $paymentsQuery->where(function ($q) use ($like) {
                $q->where('reference', 'like', $like)
                    ->orWhere('donor_name', 'like', $like)
                    ->orWhere('donor_email', 'like', $like)
                    ->orWhere('donor_phone', 'like', $like);
            });
        }

        return [
            'paymentsPendingCount' => Payment::where('status', 'pending')->count(),
            'paymentsFailedCount' => Payment::where('status', 'failed')->count(),
            'paymentStatus' => $paymentStatus,
            'paymentSearch' => $paymentSearch,
            'payments' => $paymentsQuery->paginate(15, ['*'], 'payments_page')->withQueryString(),
        ];
    }

    private function contentData(Request $request): array
    {
        $hadithSearch = trim((string) $request->get('hadith_search', ''));
        $hadithsQuery = Hadith::with('chapter.book.collection')->latest('id');

        if ($hadithSearch !== '') {
            $like = '%' . $this->escapeLike($hadithSearch) . '%';
            $hadithsQuery->where(function ($q) use ($like) {
                $q->where('swahili_text', 'like', $like)->orWhere('hadith_number', 'like', $like);
            });
        }

        return [
            'totalHadithPublished' => Hadith::where('is_published', true)->count(),
            'totalDuas' => Dua::count(),
            'totalDuasPublished' => Dua::where('is_published', true)->count(),
            'totalAyahs' => QuranAyah::count(),
            'hadithSearch' => $hadithSearch,
            'hadiths' => $hadithsQuery->paginate(10, ['*'], 'hadiths_page')->withQueryString(),
        ];
    }

    private function systemData(): array
    {
        return [
            'systemInfo' => [
                'payment_gateway' => config('services.payment_gateway', 'auto'),
                'clickpesa_configured' => ! empty(config('services.clickpesa.client_id')) && ! empty(config('services.clickpesa.api_key')),
                'pesapal_configured' => ! empty(config('services.pesapal.consumer_key')) && ! empty(config('services.pesapal.consumer_secret')),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'db_driver' => config('database.default'),
                'server_time' => now()->isoFormat('LLLL'),
            ],
            'adminLogs' => AdminLog::with('user')->latest('id')->limit(15)->get(),
        ];
    }

    private function escapeLike(string $value): string
    {
        return addcslashes($value, '%_\\');
    }

    /** Hifadhi kumbukumbu ya kitendo cha admin (haivunji kitendo kikishindwa). */
    private function audit(string $action, string $description, ?Model $target = null): void
    {
        try {
            AdminLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'description' => mb_substr($description, 0, 255),
                'target_type' => $target ? class_basename($target) : null,
                'target_id' => $target?->getKey(),
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** Zuia CSV/Excel formula injection (=, +, -, @) kwenye data iliyoandikwa na watumiaji. */
    private function csvSafe(mixed $value): string
    {
        $value = (string) $value;

        if ($value !== '' && preg_match('/^[=+\-@\t\r]/', $value) && ! preg_match('/^\+?[0-9][0-9\s\-]*$/', $value)) {
            return "'" . $value;
        }

        return $value;
    }

    /**
     * Mpe au mnyang'anye mtumiaji mamlaka ya Admin.
     */
    public function toggleUserAdmin(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Huwezi kujiondolea u-admin mwenyewe ili usijifungie nje ya jopo.');
        }

        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return redirect()->back()->with('error', 'Huwezi kumwondolea u-admin admin wa mwisho aliyebaki.');
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $action = $user->is_admin ? 'amepewa' : 'ameondolewa';
        $this->audit($user->is_admin ? 'user.admin_granted' : 'user.admin_revoked', "{$user->email} {$action} hadhi ya Admin", $user);

        return redirect()->back()->with('success', "Mtumiaji {$user->name} ({$user->email}) {$action} hadhi ya Admin kikamilifu.");
    }

    /**
     * Washa au zima uanachama wa Supporter kwa mtumiaji.
     */
    public function toggleUserSupporter(User $user): RedirectResponse
    {
        $user->is_supporter = ! $user->is_supporter;
        if ($user->is_supporter && ! $user->supporter_since) {
            $user->supporter_since = now();
        }
        $user->save();

        $action = $user->is_supporter ? 'amewezeshwa kuwa' : 'ameondolewa kwenye';
        $this->audit($user->is_supporter ? 'user.supporter_on' : 'user.supporter_off', "{$user->email} {$action} Supporter", $user);

        return redirect()->back()->with('success', "Mtumiaji {$user->name} {$action} orodha ya Waungaji Mkono (Supporters).");
    }

    /**
     * Washa au zima uchapishaji wa Hadithi.
     */
    public function toggleHadithPublish(Hadith $hadith): RedirectResponse
    {
        $hadith->is_published = ! $hadith->is_published;
        $hadith->save();

        $status = $hadith->is_published ? 'imechapishwa hadharani' : 'imefichwa (draft)';
        $this->audit($hadith->is_published ? 'hadith.published' : 'hadith.hidden', "Hadithi #{$hadith->hadith_number} {$status}", $hadith);

        return redirect()->back()->with('success', "Hadithi #{$hadith->hadith_number} sasa {$status}.");
    }

    /**
     * Washa au zima uchapishaji wa Duaa.
     */
    public function toggleDuaPublish(Dua $dua): RedirectResponse
    {
        $dua->is_published = ! $dua->is_published;
        $dua->save();

        $status = $dua->is_published ? 'imechapishwa' : 'imefichwa';
        $this->audit($dua->is_published ? 'dua.published' : 'dua.hidden', "Duaa '{$dua->title}' {$status}", $dua);

        return redirect()->back()->with('success', "Duaa '{$dua->title}' sasa {$status}.");
    }

    /**
     * Safisha cache zote za mfumo kwa mbofyo mmoja.
     */
    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('optimize:clear');
            $this->audit('system.cache_cleared', 'Cache ya mfumo imesafishwa');

            return redirect()->back()->with('success', 'Cache ya mfumo, views, routes, na usanidi (config) zimesafishwa kwa ukamilifu!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Hitilafu wakati wa kusafisha cache: ' . $e->getMessage());
        }
    }

    /**
     * Hamisha miamala ya malipo kwenye faili la CSV.
     */
    public function exportPayments(): StreamedResponse
    {
        $this->audit('payments.exported', 'Malipo yamehamishwa kwenye CSV');

        $fileName = 'malipo_hadith_app_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM kwa ajili ya usomaji mzuri Excel
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Kumbukumbu',
                'Tarehe',
                'Mchangiaji',
                'Barua Pepe',
                'Simu',
                'Kiasi',
                'Sarafu',
                'Lango',
                'Njia',
                'Hali',
                'Ujumbe',
            ]);

            Payment::latest()->chunk(200, function ($payments) use ($file) {
                foreach ($payments as $p) {
                    fputcsv($file, [
                        $this->csvSafe($p->reference),
                        $p->created_at ? $p->created_at->format('Y-m-d H:i') : '',
                        $p->is_anonymous ? 'Binafsi (Anonymous)' : $this->csvSafe($p->donor_name ?: 'Bila Jina'),
                        $this->csvSafe($p->donor_email ?: '-'),
                        $this->csvSafe($p->donor_phone ?: '-'),
                        number_format($p->amount, 2, '.', ''),
                        $this->csvSafe($p->currency),
                        $this->csvSafe(strtoupper((string) $p->gateway)),
                        $this->csvSafe($p->payment_method ?: '-'),
                        strtoupper($p->status),
                        $this->csvSafe($p->message ?: ''),
                    ]);
                }
            });

            fclose($file);
        }, 200, $headers);
    }
}
