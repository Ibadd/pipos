<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Events\StokEvent;
use App\Http\Requests\Kasir\BayarRequest;
use App\Http\Resources\errorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:PESANAN_READ')->only('index');
    }

    public function index()
    {
        return view('member.pesanan.index');
        // $pemesanans = Pemesanan::all();
        // dd($pemesanans);
    }

    public function bayar(BayarRequest $request)
    {
        DB::beginTransaction();
        try {
            Pemesanan::create($request->only(['total', 'bayar', 'kembali', 'items', 'member_id', 'member_detail']));

            // insert to history stok
            // foreach ($request->items as $item) {
            //     $produk_id = Produk::query()->where('uuid', $item['id'])->first()->id;

            //     $stok = Stok::create([
            //         'tipe' => 3,
            //         'produk_id' => $produk_id,
            //         'qty' => $item['qty'],
            //     ]);

            //     StokEvent::dispatch($stok);
            // }

            DB::commit();

            return new SuccessResource(['message' => 'Transaksi berhasil disimpan']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return new errorResource(['message' => $th->getMessage()]);
        }
    }
}
