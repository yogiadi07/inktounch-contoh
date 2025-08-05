@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">{{ $title }}</h2>

    {{-- Form Filter --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-4 items-end">
        <div>
            <label class="text-sm block">Jenis Laporan</label>
            <select name="type" class="border rounded p-2">
                <option value="harian" {{ request('type') == 'harian' ? 'selected' : '' }}>Harian</option>
                <option value="mingguan" {{ request('type') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ request('type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>

        <div>
            <label class="text-sm block">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="border rounded p-2">
        </div>

        <div>
            <label class="text-sm block">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="border rounded p-2">
        </div>

        <button class="bg-blue-600 text-black px-4 py-2 rounded">Tampilkan</button>
    </form>
    @if(request('start_date') && request('end_date'))
    <a href="{{ route('reports.export', request()->query()) }}"
       class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700">
        📥 Export Excel
    </a>
@endif

    @if($transactions->count())
        <table class="w-full table-auto border mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Invoice</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Metode</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($transactions as $tx)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y H:i') }}</td>
                        <td class="px-4 py-2 font-mono">{{ $tx->no_invoice }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($tx->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ $tx->payment_method }}</td>
                    </tr>
                    @php $total += $tx->total_price; @endphp
                @endforeach
                <tr class="font-semibold bg-gray-50 border-t">
                    <td colspan="2" class="px-4 py-2 text-right">Total Omzet</td>
                    <td colspan="2" class="px-4 py-2">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <tr class="font-semibold bg-gray-50 border-t">
            <td colspan="2" class="px-4 py-2 text-right">Total Transaksi</td>
            <td colspan="2" class="px-4 py-2">{{ $transactions->count() }} transaksi</td>
        </tr>
        <tr class="font-semibold bg-gray-50 border-t">
            <td colspan="2" class="px-4 py-2 text-right">Total Omzet</td>
            <td colspan="2" class="px-4 py-2">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        
    @else
        <p class="text-gray-500">Tidak ada transaksi pada rentang waktu ini.</p>
    @endif
</div>
@endsection
