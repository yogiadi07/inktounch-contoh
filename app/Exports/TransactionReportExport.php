<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Transaction::whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->orderBy('transaction_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No Invoice',
            'Total',
            'Dibayar',
            'Kembalian',
            'Metode Pembayaran',
        ];
    }

    public function map($row): array
    {
        return [
            Carbon::parse($row->transaction_date)->format('d-m-Y H:i'),
            $row->no_invoice,
            'Rp ' . number_format($row->total_price, 0, ',', '.'),
            'Rp ' . number_format($row->paid_amount, 0, ',', '.'),
            'Rp ' . number_format($row->change_amount, 0, ',', '.'),
            $row->payment_method,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}

