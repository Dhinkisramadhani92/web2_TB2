<?php

namespace App\Http\Controllers;

use App\Models\produk;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function viewHome()
    {
        //apakah  user  adalah admin
        $isadmin =Auth::user()->role === 'admin';
        //ambil produk dari database dan kelompokan  berdasarkan tanggal
        $produkPerHari = Produk::selectRaw('DATE(create_at) as  date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc');
            //filter by user_id jika user bikan admin
            if (!$isadmin){
                $produkPerHariQuery->where('used_id', Auth::id());
            }
            $produkPerHari = $produkPerHariQuery->get();

        //misalkan data untuk grafik
        $dates = [];
        $totals = [];

        foreach($produkPerHari as $item){
            $dates[] = Carbon::parse($item->date)->format('Y-m-d'); //format tanggal
            $totals[] = $item->total;
        }
        // membuat fraffik menggunakan data yang diabil
        $chart = LarapexChart::barChart()
            ->setTitle('Produk Ditambahkan Per Hari')
            ->setSubtitle('Data Penambahan Produk Harian')
            ->addData('Jumlah Produk', $totals)
            ->setXAxis($dates);

            //data tambahan untk view
            $totalProductsQuery = Produk::query();;

            //filer by user_id if the user  is not an admin
            if (!$isadmin){
                $produkPerHariQuery->where('used_id', Auth::id());
            }

        //data tambahkan untuk view
        $data = [
            'totalProducts'=>produk::count(), //Total Produk sesuai role
            'salesToday'=>130,
            'totalRevenue'=> 'Rp.75.000.000',
            'regusteredUser'=>350,
            'chart' =>$chart //pass chart ke view
        ];

        return view('home', $data);
    }
}
