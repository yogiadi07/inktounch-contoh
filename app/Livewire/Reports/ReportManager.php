<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Exports\TransactionReportExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
class ReportManager extends Component
{
    public $start_date = '';
    public $end_date = '';
    public $report_type = 'daily';
    public $category_id = '';
    
    public $salesData = [];
    public $topProducts = [];
    public $categoryStats = [];
    public $totalRevenue = 0;
    public $totalTransactions = 0;
    public $averageTransaction = 0;

    public function mount()
    {
        $this->start_date = Carbon::today()->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
        $this->generateReport();
    }

    public function generateReport()
    {
        $startDate = $this->start_date ? Carbon::parse($this->start_date)->startOfDay() : Carbon::today()->startOfDay();
        $endDate = $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : Carbon::today()->endOfDay();

        // Basic statistics
        $query = Transaction::whereBetween('transaction_date', [$startDate, $endDate]);
        
        $this->totalRevenue = $query->sum('total_price');
        $this->totalTransactions = $query->count();
        $this->averageTransaction = $this->totalTransactions > 0 ? $this->totalRevenue / $this->totalTransactions : 0;

        // Sales data based on report type
        $this->salesData = $this->getSalesData($startDate, $endDate);
        
        // Top products
        $this->topProducts = $this->getTopProducts($startDate, $endDate);
        
        // Category statistics
        $this->categoryStats = $this->getCategoryStats($startDate, $endDate);
    }

    private function getSalesData($startDate, $endDate)
    {
        $format = match($this->report_type) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d'
        };

        return Transaction::selectRaw("
                DATE_FORMAT(transaction_date, '{$format}') as period,
                COUNT(*) as transaction_count,
                SUM(total_price) as total_revenue
            ")
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => $item->period,
                    'transaction_count' => $item->transaction_count,
                    'total_revenue' => $item->total_revenue,
                    'formatted_period' => $this->formatPeriod($item->period)
                ];
            });
    }

    private function getTopProducts($startDate, $endDate)
    {
        return DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->when($this->category_id, function ($query) {
                return $query->where('products.category_id', $this->category_id);
            })
            ->selectRaw('
                products.name,
                products.category_id,
                SUM(transaction_details.quantity) as total_sold,
                SUM(transaction_details.subtotal) as total_revenue
            ')
            ->groupBy('products.id', 'products.name', 'products.category_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
    }

    private function getCategoryStats($startDate, $endDate)
    {
        return DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
            ->selectRaw('
                categories.name as category_name,
                COUNT(DISTINCT transactions.id) as transaction_count,
                SUM(transaction_details.quantity) as total_quantity,
                SUM(transaction_details.subtotal) as total_revenue
            ')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
    }

    private function formatPeriod($period)
    {
        return match($this->report_type) {
            'daily' => Carbon::parse($period)->format('d M Y'),
            'weekly' => 'Minggu ' . substr($period, -2) . ' - ' . substr($period, 0, 4),
            'monthly' => Carbon::createFromFormat('Y-m', $period)->format('M Y'),
            default => $period
        };
    }

    public function exportReport()
    {
        $startDate = $this->start_date ? Carbon::parse($this->start_date)->startOfDay() : Carbon::today()->startOfDay();
        $endDate = $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : Carbon::today()->endOfDay();

        $filename = 'laporan_penjualan_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.xlsx';

        return Excel::download(new TransactionReportExport($startDate, $endDate), $filename);
    }

    public function updatedStartDate()
    {
        $this->generateReport();
    }

    public function updatedEndDate()
    {
        $this->generateReport();
    }

    public function updatedReportType()
    {
        $this->generateReport();
    }

    public function updatedCategoryId()
    {
        $this->generateReport();
    }

    public function render()
    {
        $categories = Category::all();
        
        return view('livewire.reports.report-manager', [
            'categories' => $categories
        ]);
    }
}
