<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
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
            \Carbon\Carbon::parse($row->transaction_date)->format('d-m-Y H:i'),
            $row->no_invoice,
            $row->total_price,
            $row->paid_amount,
            $row->change_amount,
            $row->payment_method,
        ];
    }
}

