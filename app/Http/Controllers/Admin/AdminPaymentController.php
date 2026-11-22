<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPaymentController extends Controller
{
    use AuditsAdminActions;

    /**
     * Hamisha miamala ya malipo kwenye faili la CSV.
     */
    public function export(): StreamedResponse
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

    /** Zuia CSV/Excel formula injection (=, +, -, @) kwenye data iliyoandikwa na watumiaji. */
    private function csvSafe(mixed $value): string
    {
        $value = (string) $value;

        if ($value !== '' && preg_match('/^[=+\-@\t\r]/', $value) && ! preg_match('/^\+?[0-9][0-9\s\-]*$/', $value)) {
            return "'" . $value;
        }

        return $value;
    }
}
