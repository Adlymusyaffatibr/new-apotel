@extends('layout.template', ['title' => 'Pembelian'])

@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('tambah.pembelian') }}" class="btn btn-primary">Pembelian Baru</a>
        </div>
        <br>
        <form action="{{ route('pembelian') }}" method="GET" class="d-flex justify-content-end mb-2">
            <input type="date" name="search_date" class="form-control" value="{{ request('search_date') }}">
            <button type="submit" class="btn btn-primary ms-2">Cari</button>
        </form>
        </br>
        <table class="table table-bordered table-stripped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pembeli</th>
                    <th>Obat</th>
                    <th>Total Bayar</th>
                    <th>Nama Kasir</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (count($orders) < 1)
                    <tr>
                        <td colspan="7" class="text-center">Data Pembelian Kosong</td>
                    </tr>
                @else
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + ($index + 1) }}</td>
                            <td>{{ $order->name_customer }}</td>
                            <td>
                                @foreach ($order->medicines as $medicine)
                                    {{ $medicine['name_medicines'] }} (Jumlah: {{ $medicine['qty'] }})<br>
                                @endforeach
                            </td>
                            <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>kasir {{ $order['user_id'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y H:i:s') }}</td>
                            <td class="d-flex">
                                {{-- <button class="btn btn-danger me-2"
                                    onclick="showModalDelete('{{ $order->id }}', '{{ $order->name_customer }}')">Hapus</button> --}}
                                <a href="{{ route('download', $order->id) }}" class="btn btn-warning me-2">Cetak</a>
                                {{-- <button class="btn btn-secondary" onclick="showEditDateModal('{{ $order->id }}', '{{ $order->created_at }}')">Edit</button> --}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-end my-3">
            {{ $orders->links() }}
        </div>
    </div>
    @endsection
