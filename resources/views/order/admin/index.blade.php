@extends('layout.template', ['title' => 'Pembelian'])

@section('content')
<div class="container mt-3">
    <div class="d-flex justify-content-end">
        <a href="" class="btn btn-warning  ">Export Data(excel)</a>

    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pembeli</th>
                <th>Obat</th>
                <th>Total Bayar</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ ($orders->currentPage()-1) * $orders->perPage() + $loop->index + 1 }}</td>
                        <td>{{ $order->name_customer }}</td>
                        <td>
                            @foreach ($order->medicines as $medicine)
                                {{ $medicine['name_medicines'] }} (Jumlah: {{ $medicine['qty'] }})<br>
                            @endforeach
                        </td>
                        <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y H:i:s') }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
    
    <div class="d-flex justify-content-end my-3">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script>
    function showModalDelete(orderId, orderName) {
        document.getElementById('orderName').innerText = orderName;
        var form = document.getElementById('deleteForm');
        form.action = '/kasir/order/' + orderId;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    function printOrder(orderId) {
    const url = {{ route('download', ':id') }}.replace(':id', orderId);  // Corrected the route here
    window.open(url, '_blank');
}


</script>
@endsection