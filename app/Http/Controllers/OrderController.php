<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Medicine;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
        $searchDate = $request->input('search_date');

        $orders = Order::with('user')
            ->when($searchDate, function ($query) use ($searchDate) {
                $query->whereDate('created_at', $searchDate);
            })
            ->paginate(10);

        foreach ($orders as $order) {
            if (is_string($order->medicines)) {
                $order->medicines = json_decode($order->medicines, true);
            }
        }
        return view('order.kasir.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $medicines = Medicine::all();
        return view("order.kasir.create", compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name_customer' => 'required|string',
            'medicines'  => 'required|array',
        ]);
        // mencari jumlah item yang sama pada array, struktur nya
        $arrayDistinct = array_count_values($request->medicines);
        $arrayMedicines = [];

        foreach ($arrayDistinct as $id => $count) {
            $medicines = Medicine::where('id', $id)->first();
            $subPrice = $medicines['price'] * $count;
            if ($medicines['stock'] < $count) {
                $valueBefore =[
                    "name_costumer" => $request->name_customer,
                    "medicines" => $request->medicines,
                ];
                $msg = "Obat " . $medicines['name'] . " sisa stok :" . $medicines['stock'] . ". Tidak dapat melakukan proses pembelian!";
                return redirect()->back()->with('failed', $msg)->withInput();
            }else{
                $medicines['stock']-= $count;
                $medicines->save();
            }

            $arrayItem = [
                "id" => $id,
                "name_medicines" => $medicines['name'],
                "qty" => $count,
                "price" => $medicines['price'],
                "sub_price" => $subPrice,
            ];

            array_push($arrayMedicines, $arrayItem);
        }
        $totalPrice = 0;
        foreach ($arrayMedicines as $item) {
            $totalPrice += (int)$item['sub_price'];
        }
        $pricePpn = $totalPrice + ($totalPrice * 0.01);

        $proses = Order::create([
            'user_id' => Auth::user()->id,
            'medicines' => $arrayMedicines,
            'name_customer' => $request->name_customer,
            'total_price' => $pricePpn,
        ]);

        if ($proses) {
            $order = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            return redirect()->route('order.kasir.show', ['id' => $order->id]);
        } else {
            return redirect()->back()->with('failed', 'Gagal membuat data pembelian. silahkan coba kembali dengan data yang sesuai');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Logika untuk mendapatkan order berdasarkan $id
        $order = Order::find($id);
        if (!$order) {
            abort(404); // Jika order tidak ditemukan
        }
        return view('order.kasir.print', compact('order'));
    }

    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDate(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
        ]);
    
        $order = Order::findOrFail($id);
        $order->created_at = $request->input('date');
        $order->save();
    
        return response()->json(['success' => true]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function downloadPdf($id)
    {
        // ambil data berdasarkan id yang ada di struk dan dipastikan terfomat array
        $order = Order::find($id)->toArray();
        // kita akan share data dengan inisial awal agar bisa digunakan ke blade manapun
        view()->share('order', $order);
        // ini akan meload view halaman downloadnya
        $pdf = PDF::loadView('order.kasir.download', $order);
        // tinggal kita download
        return $pdf->download('receipt.pdf');
    }

    public function data()
    {
        $orders = Order::with('user')->simplePaginate(10);
            foreach ($orders as $order) {
            $order->medicine = json_decode($order->medicine, true);
        }
    
        return view("order.admin.index", compact('orders'));
    }
}
