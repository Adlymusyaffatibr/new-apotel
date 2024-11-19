@extends('layout.template')

@section('content')
<div class="container mt-5">

    <!-- Form Container -->
    <form action="{{ route('store.pembelian') }}" method="POST" class="card m-auto p-5 shadow-lg rounded-lg" style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
        @csrf

        {{-- Validasi error --}}
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="font-size: 16px; border-radius: 8px; background-color: #f8d7da; color: #721c24; padding: 12px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <!-- Penanggung Jawab -->
        <p class="mb-4" style="font-size: 18px; color: #333; font-weight: 600;">
            Penanggung Jawab: <b>{{ Auth::user()->nama }}</b>
        </p>

        <!-- Nama Pembeli -->
        <div class="mb-4 row">
            <label for="name_customer" class="col-sm-3 col-form-label label-style">Nama Pembeli:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control form-control-lg" id="name_customer" name="name_customer" value="{{ old('name_customer') }}" required style="border-radius: 10px; padding: 14px; font-size: 16px; background-color: #f1f1f1; border: 1px solid #ddd;">
            </div>
        </div>

        <!-- Obat -->
        <div id="medicines-container">
            @if (old('medicines'))
                @foreach (old('medicines') as $no => $item)
                    <div class="mb-4 row" id="medicines-{{ $no }}">
                        <label class="col-sm-3 col-form-label label-style">
                            Obat {{ $no + 1 }}
                            @if ($no > 0)
                                <span style="cursor: pointer; font-weight: bold; padding: 5px; color:red;" onclick="deleteSelect('medicines-{{ $no }}')">Hapus</span>
                            @endif
                        </label>
                        <div class="col-sm-9">
                            <select name="medicines[]" class="form-select form-select-lg" required style="border-radius: 10px; padding: 14px; font-size: 16px; background-color: #f1f1f1; border: 1px solid #ddd;">
                                <option selected hidden disabled>Pesanan {{ $no + 1 }}</option>
                                @foreach ($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" {{ $medicine->id == $item ? 'selected' : '' }}>{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="mb-4 row" id="medicines-0">
                    <label class="col-sm-3 col-form-label label-style">Obat</label>
                    <div class="col-sm-9">
                        <select name="medicines[]" class="form-select form-select-lg" required style="border-radius: 10px; padding: 14px; font-size: 16px; background-color: #f1f1f1; border: 1px solid #ddd;">
                            <option selected hidden disabled>Pesanan 1</option>
                            @foreach ($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>

        <p class="text-primary" id="add-select" style="cursor: pointer; font-weight: 500; font-size: 16px; color: #28a745; transition: color 0.3s ease;">
            + Tambah Obat
        </p>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success btn-block" style="font-weight: 600; padding: 14px 0; font-size: 18px; border-radius: 10px; transition: all 0.3s ease;">
            Konfirmasi Pembelian
        </button>
    </form>
</div>
@endsection

@push('script')
<script>
    let no = {{ old('medicines') ? count(old('medicines')) + 1 : 2 }};

    $("#add-select").on("click", function() {
        let html = `
            <div class="mb-4 row" id="medicines-${no}">
                <label class="col-sm-3 col-form-label label-style">Obat ${no}
                    <span style="cursor: pointer; font-weight: bold; padding: 5px; color:red;" onclick="deleteSelect('medicines-${no}')">Hapus</span>
                </label>
                <div class="col-sm-9">
                    <select name="medicines[]" class="form-select form-select-lg" required style="border-radius: 10px; padding: 14px; font-size: 16px; background-color: #f1f1f1; border: 1px solid #ddd;">
                        <option selected hidden disabled>Pesanan ${no}</option>
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>`;
        
        $("#medicines-container").append(html);
        no++;
    });

    function deleteSelect(elementId){
        $("#" + elementId).remove();
        no--;
    }
</script>
@endpush

<style>
    /* Label styling */
    .label-style {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        transition: color 0.3s ease, transform 0.3s ease;
    }
    /* Hover effect on labels */
    .label-style:hover {
        color: #28a745;
        transform: translateX(4px);
    }
    /* Hover effect for Add Select */
    #add-select:hover {
        color: #218838;
        text-decoration: underline;
    }
    /* Button hover effect */
    button:hover {
        background-color: #218838;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    /* Card Hover Effect */
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    /* Custom styling for alerts */
    .alert {
        margin-bottom: 15px;
        border-radius: 8px;
    }
</style>