<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\TransactionReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
{
    $type = $request->get('type'); // harian, mingguan, bulanan
    $start = $request->get('start_date');
    $end = $request->get('end_date');

    $transactions = collect(); // default kosong
    $title = 'Laporan Transaksi';
    $grouped = [];

    if ($start && $end) {
        $query = Transaction::whereBetween('transaction_date', [
            $start . ' 00:00:00',
            $end . ' 23:59:59',
        ]);

        $transactions = $query->orderBy('transaction_date')->get();

        $title = match($type) {
            'harian'   => 'Laporan Harian',
            'mingguan' => 'Laporan Mingguan',
            'bulanan'  => 'Laporan Bulanan',
            default    => 'Laporan Transaksi'
        };

        if ($type === 'mingguan') {
            $grouped = $transactions->groupBy(function ($tx) {
                return Carbon::parse($tx->transaction_date)->format('Y-m-d');
            });
        } elseif ($type === 'bulanan') {
            $grouped = $transactions->groupBy(function ($tx) {
                return Carbon::parse($tx->transaction_date)->format('Y-m');
            });
        }
    }

    return view('reports.index', compact('transactions', 'type', 'start', 'end', 'title', 'grouped'));
}


public function exportExcel(Request $request)
{
    $start = $request->start_date;
    $end = $request->end_date;

    $transactions = Transaction::whereBetween('transaction_date', [
        $start . ' 00:00:00',
        $end . ' 23:59:59',
    ])->orderBy('transaction_date')->get();

    $filename = 'laporan_transaksi_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(new TransactionReportExport($transactions), $filename);
}

}
