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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $sevenDaysAgo = Carbon::today()->subDays(6)->toDateString();
        $thirtyDaysAgo = Carbon::today()->subDays(29)->toDateString();

        // 1. Traffic & Visitors Analytics
        $viewsToday = PageView::where('viewed_date', $today)->count();
        $uniqueToday = PageView::where('viewed_date', $today)->distinct('ip_hash')->count('ip_hash');

        $views7Days = PageView::where('viewed_date', '>=', $sevenDaysAgo)->count();
        $unique7Days = PageView::where('viewed_date', '>=', $sevenDaysAgo)->distinct('ip_hash')->count('ip_hash');

        $views30Days = PageView::where('viewed_date', '>=', $thirtyDaysAgo)->count();
        $unique30Days = PageView::where('viewed_date', '>=', $thirtyDaysAgo)->distinct('ip_hash')->count('ip_hash');

        $viewsAllTime = PageView::count();

        // Daily pageviews breakdown for last 7 days
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

        // Top 7 most viewed pages
        $topPages = PageView::select('path', DB::raw('count(*) as total'))
            ->where('viewed_date', '>=', $thirtyDaysAgo)
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // Device breakdown
        $deviceMobile = PageView::whereIn('device_type', ['mobile', 'tablet'])->count();
        $deviceDesktop = PageView::where('device_type', 'desktop')->count();
        $deviceTotal = $deviceMobile + $deviceDesktop;
        $mobilePercent = $deviceTotal > 0 ? round(($deviceMobile / $deviceTotal) * 100) : 0;
        $desktopPercent = $deviceTotal > 0 ? (100 - $mobilePercent) : 0;

        // 2. Payments & Donations Analytics
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

        // Payments list with filtering and search
        $paymentStatus = $request->get('status', 'all');
        $paymentSearch = trim((string) $request->get('search', ''));

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

        $payments = $paymentsQuery->paginate(15)->withQueryString();

        // 3. System & Content Counts
        $totalUsers = User::count();
        $totalSupporters = User::where('is_supporter', true)->count();
        $totalHadith = Hadith::count();
        $totalAyahs = QuranAyah::count();
        $totalDuas = Dua::count();

        return view('admin.dashboard', compact(
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
            'totalSupporters',
            'totalHadith',
            'totalAyahs',
            'totalDuas'
        ));
    }

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
            
            // UTF-8 BOM for Excel Swahili compatibility
            fputs($file, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($file, [
                'Namba ya Kumbukumbu',
                'Tarehe',
                'Jina la Mchangiaji',
                'Barua Pepe',
                'Simu',
                'Kiasi',
                'Sarafu',
                'Lengo / Aina',
                'Njia ya Malipo',
                'Hali ya Malipo',
                'Ujumbe wa Mchangiaji',
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
                        $p->purpose ?: 'Sadaka',
                        $p->payment_method ?: $p->gateway,
                        strtoupper($p->status),
                        $p->message ?: '',
                    ]);
                }
            });

            fclose($file);
        }, 200, $headers);
    }
}
