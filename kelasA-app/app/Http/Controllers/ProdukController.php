<?php

namespace App\Http\Controllers;

use App\Models\produk;
use App\Http\Requests\StoreprodukRequest;
use App\Http\Requests\UpdateprodukRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ViewProduk()
    {
        // $produk = Produk::all(); //mengambil semuadata di table produk
        $isAdmin = Auth::user()->role == 'admin';
        //jika user adalah admin,maka menampuilkan semua data, jika bukan maka tampilkan user_id yang sama dengan user lain
        $produk = $isAdmin ? Produk::all() : Produk::where('user_id', Auth::user()->id)->get();

        return view('produk', ['produk' => $produk]); //menampilkan view dari produk.blade.php dengan membawa variabel $produk
    }

    public function CreateProduk(Request $request)
    {
        //menambahkan variabel $filepath untuk mendefenisikan penyimpanan file
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() .'_'. $imageFile->getClientOriginalName();
            $imageFile->storeAs('public/images',$imageName); //Store image in the'storage/app/public
        }
        Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'jumlah_produk' => $request->jumlah_produk
        ]);
        return redirect(Auth::user()->role. '/produk');
    }

    public function ViewAddproduk()
    {
        return view('addproduk'); //menampilkan view dari addproduk.blade.php
    }

    public function DeleteProduk($kode_produk)
    {
        produk::where('kode_produk', $kode_produk)->delete(); //find the record by ID

        //Redirect back to  the  index page  with a success message
        return redirect(Auth::user()->role. '/produk');
    }

    //Fungsi untuk view edit produk
    public function ViewEditProduk($kode_produk)
    {
        $ubahproduk = produk::where('kode_produk', $kode_produk)->first();

        return view('editproduk', compact('ubahproduk'));
    }
    //Fungsi menambah data produk
    public function UpdateProduk(Request $request,$kode_produk)
    {
        //menambahkan variabel $filePath untuk mendefinisikan penyimpanan file
        $imageName = null;
        if ($request->hasFile('image')){
            $imageFile = $request->file('image');
            $imageName = time() .'_' . $imageFile->getClientOriginalName();
            $imageFile->storeAs('public/images', $imageName); //store imagein the 'storage/app/public
        }
        Produk::where('kode_produk',$kode_produk)->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'jumlah_produk' => $request->jumlah_produk,
            'image' => $imageName,
            'user_id' =>Auth::user()->id
        ]);
        return redirect(Auth::user()->role. '/produk');
    }

    public function ViewLaporan()
    {
        //mengambil semua data produk
        $products = Produk::all();
        return view ('laporan',['products' => $products]);
    }
    public function print()
    {
        //mengambil semua data produk
        $products = Produk::all();

        //load view untuk pdf dengan data produk
        $pdf =Pdf::loadView('viewreport', compact('products'));

        //menampilkan langsung pdf dari browser
        return $pdf->stream('laporan-produk.pdf');
    }
}
