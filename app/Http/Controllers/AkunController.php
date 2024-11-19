<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AkunController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function login(){
        return view('login');
    }

    public function loginAuth(Request $request){
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $users = $request->only(['email','password']);
        if(Auth::attempt($users)) {
            return redirect()->route('home');
        } else {
            return redirect()->back()->with('failed','proses login gagal,coba kembali dengan data yang benar!   ');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('logout', 'anda telah logout');
    }

    public function index()
    {
        $users = User::all();
        return view("kelola.index",compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelola.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi data input
    $validatedData= $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:akuns,email', // Memastikan email unik
        'role' => 'required|string|in:admin,member',
    ]);

    // Cek jika sudah ada data dengan kombinasi nama, email, dan role yang sama
    $existingAkun = User::where('nama', $validatedData['nama'])
                        ->where('email', $validatedData['email'])
                        ->where('role', $validatedData['role'])
                        ->first();

    if ($existingAkun) {
        // Jika data sudah ada, kembalikan pesan error
        return redirect()->back()->withErrors('Data dengan kombinasi nama, email, dan role yang sama sudah ada!');
    }

    // Membuat password acak
    $generatedPassword= Str::random(12);

    // Jika tidak ada duplikasi, simpan data baru
    User::create([
        'nama' => $validatedData['nama'],
        'email' => $validatedData['email'],
        'password' => bcrypt($generatedPassword),
        'role' => $validatedData['role'],
    ]);

    // Redirect dengan pesan sukses
    return redirect()->back()->with('success', 'Berhasil Menambahkan Data!');
}

    /**
     * Display the specified resource.
     */
    public function show(User $akun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $users = User::find($id);
        return view('kelola.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required',
            'role' => 'required',
        ]);

        User::where('id', $id)->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('kelola.home')->with('success', 'Berhasil Mengubah Data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        User::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Berhasil menghapus data');
    }
}
