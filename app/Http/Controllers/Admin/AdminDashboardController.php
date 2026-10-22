<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dua;
use App\Models\Hadith;
use App\Models\PageView;
use App\Models\Payment;
use App\Models\QuranAyah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'overview');

        $today = Carbon::today()->toDateString();
        $sevenDaysAgo = Carbon::today()->subDays(6)->toDateString();
        $thirtyDaysAgo = Carbon::today()->subDays(29)->toDateString();

        // ---------------------------------------------------------
        // 1. TRAFIKI & WATAZAMAJI (Analytics)
        // ---------------------------------------------------------
        $viewsToday = PageView::where('viewed_date', $today)->count();
        $uniqueToday = PageView::where('viewed_date', $today)->distinct('ip_hash')->count('ip_hash');

        $views7Days = PageView::where('viewed_date', '>=', $sevenDaysAgo)->count();
        $unique7Days = PageView::where('viewed_date', '>=', $sevenDaysAgo)->distinct('ip_hash')->count('ip_hash');

        $views30Days = PageView::where('viewed_date', '>=', $thirtyDaysAgo)->count();
        $unique30Days = PageView::where('viewed_date', '>=', $thirtyDaysAgo)->distinct('ip_hash')->count('ip_hash');

        $viewsAllTime = PageView::count();

        // Takwimu za siku 7 za karibuni
        $rawDailyViews = PageView::select('viewed_date', DB::raw('count(*) as total'))
            ->where('viewed_date', '>=', $sevenDaysAgo)
            ->groupBy('viewed_date')
            ->pluck('total', 'viewed_date')
            ->toArray();

        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $label = Carbon::today()->subDays($i)->isoFormat('ddd D');
            $dailyStats[] = [
                'date' => $date,
                'label' => $label,
                'count' => $rawDailyViews[$date] ?? 0,
            ];
        }

        // Kurasa zinazoongoza
        $topPages = PageView::select('path', DB::raw('count(*) as total'))
            ->where('viewed_date', '>=', $thirtyDaysAgo)
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // Vifaa
        $deviceMobile = PageView::whereIn('device_type', ['mobile', 'tablet'])->count();
        $deviceDesktop = PageView::where('device_type', 'desktop')->count();
        $deviceTotal = $deviceMobile + $deviceDesktop;
        $mobilePercent = $deviceTotal > 0 ? round(($deviceMobile / $deviceTotal) * 100) : 0;
        $desktopPercent = $deviceTotal > 0 ? (100 - $mobilePercent) : 0;

        // Ziara za karibuni zaidi (Real-time live visitor stream)
        $recentViews = PageView::with('user')->latest('id')->limit(12)->get();

        // ---------------------------------------------------------
        // 2. MAPATO YA SADAKA & MALIPO (ClickPesa / Pesapal)
        // ---------------------------------------------------------
        $totalTzs = Payment::where('status', 'completed')
            ->where('currency', 'TZS')
            ->sum('amount');

        $totalUsd = Payment::where('status', 'completed')
            ->where('currency', 'USD')
            ->sum('amount');

        $startOfMonth = Carbon::now()->startOfMonth();
        $monthTzs = Payment::where('status', 'completed')
            ->where('currency', 'TZS')
            ->where('completed_at', '>=', $startOfMonth)
            ->sum('amount');

        $monthUsd = Payment::where('status', 'completed')
            ->where('currency', 'USD')
            ->where('completed_at', '>=', $startOfMonth)
            ->sum('amount');

        $paymentsCompletedCount = Payment::where('status', 'completed')->count();
        $paymentsPendingCount = Payment::where('status', 'pending')->count();
        $paymentsFailedCount = Payment::where('status', 'failed')->count();

        // Orodha ya malipo yenye uchujaji na utafutaji
        $paymentStatus = $request->get('payment_status', 'all');
        $paymentSearch = trim((string) $request->get('payment_search', ''));

        $paymentsQuery = Payment::query()->latest();

        if ($paymentStatus !== 'all' && in_array($paymentStatus, ['completed', 'pending', 'failed'])) {
            $paymentsQuery->where('status', $paymentStatus);
        }

        if ($paymentSearch !== '') {
            $paymentsQuery->where(function ($q) use ($paymentSearch) {
                $q->where('reference', 'like', "%{$paymentSearch}%")
                    ->orWhere('donor_name', 'like', "%{$paymentSearch}%")
                    ->orWhere('donor_email', 'like', "%{$paymentSearch}%")
                    ->orWhere('donor_phone', 'like', "%{$paymentSearch}%");
            });
        }

        $payments = $paymentsQuery->paginate(15, ['*'], 'payments_page')->withQueryString();

        // ---------------------------------------------------------
        // 3. USIMAMIZI KAMILI WA WATUMIAJI (User Management)
        // ---------------------------------------------------------
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalSupporters = User::where('is_supporter', true)->count();
        $totalRegularUsers = $totalUsers - $totalAdmins;

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
            $usersQuery->where(function ($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                    ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }

        $users = $usersQuery->paginate(12, ['*'], 'users_page')->withQueryString();

        // ---------------------------------------------------------
        // 4. MAUDHUI (Hadith, Duaa, Quran)
        // ---------------------------------------------------------
        $totalHadith = Hadith::count();
        $totalHadithPublished = Hadith::where('is_published', true)->count();

        $totalDuas = Dua::count();
        $totalDuasPublished = Dua::where('is_published', true)->count();

        $totalAyahs = QuranAyah::count();

        $hadithSearch = trim((string) $request->get('hadith_search', ''));
        $hadithsQuery = Hadith::with('chapter.book.collection')->latest('id');

        if ($hadithSearch !== '') {
            $hadithsQuery->where(function ($q) use ($hadithSearch) {
                $q->where('swahili_text', 'like', "%{$hadithSearch}%")
                    ->orWhere('hadith_number', 'like', "%{$hadithSearch}%");
            });
        }

        $hadiths = $hadithsQuery->paginate(10, ['*'], 'hadiths_page')->withQueryString();

        // ---------------------------------------------------------
        // 5. TAARIFA ZA MFUMO (System Info & Gateways)
        // ---------------------------------------------------------
        $systemInfo = [
            'payment_gateway' => config('services.payment_gateway', 'auto'),
            'clickpesa_configured' => ! empty(config('services.clickpesa.client_id')) && ! empty(config('services.clickpesa.api_key')),
            'pesapal_configured' => ! empty(config('services.pesapal.consumer_key')) && ! empty(config('services.pesapal.consumer_secret')),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_driver' => config('database.default'),
            'server_time' => now()->isoFormat('LLLL'),
        ];

        return view('admin.dashboard', compact(
            'activeTab',
            'viewsToday',
            'uniqueToday',
            'views7Days',
            'unique7Days',
            'views30Days',
            'unique30Days',
            'viewsAllTime',
            'dailyStats',
            'topPages',
            'mobilePercent',
            'desktopPercent',
            'recentViews',
            'totalTzs',
            'totalUsd',
            'monthTzs',
            'monthUsd',
            'paymentsCompletedCount',
            'paymentsPendingCount',
            'paymentsFailedCount',
            'payments',
            'paymentStatus',
            'paymentSearch',
            'totalUsers',
            'totalAdmins',
            'totalSupporters',
            'totalRegularUsers',
            'users',
            'userRole',
            'userSearch',
            'totalHadith',
            'totalHadithPublished',
            'totalDuas',
            'totalDuasPublished',
            'totalAyahs',
            'hadiths',
            'hadithSearch',
            'systemInfo'
        ));
    }

    /**
     * Mpe au mnyang'anye mtumiaji mamlaka ya Admin.
     */
    public function toggleUserAdmin(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Huwezi kujiondolea u-admin mwenyewe ili usijifungie nje ya jopo.');
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $action = $user->is_admin ? 'amepewa' : 'ameondolewa';

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

        return redirect()->back()->with('success', "Duaa '{$dua->title}' sasa {$status}.");
    }

    /**
     * Safisha cache zote za mfumo kwa mbofyo mmoja.
     */
    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('optimize:clear');

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
                        $p->reference,
                        $p->created_at ? $p->created_at->format('Y-m-d H:i') : '',
                        $p->is_anonymous ? 'Binafsi (Anonymous)' : ($p->donor_name ?: 'Bila Jina'),
                        $p->donor_email ?: '-',
                        $p->donor_phone ?: '-',
                        number_format($p->amount, 2, '.', ''),
                        $p->currency,
                        strtoupper((string) $p->gateway),
                        $p->payment_method ?: '-',
                        strtoupper($p->status),
                        $p->message ?: '',
                    ]);
                }
            });

            fclose($file);
        }, 200, $headers);
    }
}
