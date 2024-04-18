<?php

namespace App\Http\Controllers\Member;

use App\Exports\BarangExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Barang\BarangCreateRequest;
use App\Http\Requests\Barang\BarangEditRequest;
use App\Http\Resources\errorResource;
use App\Http\Resources\Barang\BarangDetailResource;
use App\Http\Resources\Barang\BarangResource;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:BARANG_READ')->only('index');
        $this->middleware('permission:BARANG_CREATE')->only(['create', 'store']);
        $this->middleware('permission:BARANG_EDIT')->only(['edit', 'update']);
        $this->middleware('permission:BARANG_DELETE')->only('delete');
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;
        $kategori = $request->kategori;

        $data = Barang::query()
            ->withWhereHas('kategori')
            ->when($kategori, fn ($e, $kategori) => $e->whereHas('kategori', fn ($e) => $e->whereIn('id', $kategori)))
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    $e->where('barcode', 'like', '%' . $cari . '%')->orWhere('produk', 'like', '%' . $cari . '%')->orWhere('keterangan', 'like', '%' . $cari . '%');
                });
            })
            ->where('status', cekStatus($request->status));

        if ($request->filled('export')) {

            activity()
                ->causedBy(Auth::id())
                ->useLog('produk export')
                ->log(request()->ip());

            return Excel::download(new BarangExport($data->get()), 'PRODUK.xlsx');
        }

        return DataTables::eloquent($data)
            ->editColumn('kategori_id', fn ($e) => $e->kategori->kategori)
            ->addColumn('unit', fn ($e) => $e->unit->unit)
            ->editColumn('foto', fn ($e) => fotoProduk($e->foto))
            ->editColumn('status', fn ($e) => statusTable($e->status))
            ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMM YYYY HH:mm'))
            ->addColumn('aksi', function ($e) {
                $user = User::find(Auth::id());

                $btnEdit = $user->hasPermissionTo('BARANG_EDIT')
                    ? ($e->status == true ? '<li><a href="' . route('barang.edit', ['barang' => $e->uuid]) . '" class="dropdown-item"><i class="bx bx-pencil"></i> Edit</a></li>' : '')
                    : '';

                $btnDelete = $user->hasPermissionTo('BARANG_DELETE')
                    ? ($e->status == true ? '<li><a href="' . route('barang.destroy', ['barang' => $e->uuid]) . '" data-title="' . $e->produk . '" class="dropdown-item btn-hapus"><i class="bx bx-trash"></i> Delete</a></li>' : '')
                    : '';

                $btnReload = $user->hasPermissionTo('BARANG_EDIT')
                    ? ($e->status == false ?  '<li><a href="' . route('barang.destroy', ['barang' => $e->uuid]) . '" data-title="' . $e->produk . '" data-status="' . $e->status . '" class="dropdown-item btn-hapus"><i class="bx bx-refresh"></i></i> reset</a></li>' : '')
                    : '';

                return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="dropdown-toggle badge border text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                    setting
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                    ' . $btnEdit . '
                                    ' . $btnDelete . '
                                    ' . $btnReload . '
                                </ul>
                            </div>
                        </div>';
            })
            ->rawColumns(['aksi', 'foto', 'status'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $produk = Produk::all();
        // dd($produk);
        return view('member.barang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BarangCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $produk = Barang::create($request->only(['kategori_id', 'barcode', 'produk', 'keterangan', 'stok_warning', 'foto', 'unit_id', 'is_app'])+ ['stok' => 99]);
            
            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            
            Log::warning($th->getMessage());
            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($produk)
    {
        try {
            $produk = Barang::where('uuid', $produk)->firstOrFail();

            if (request()->detail) {
                return new BarangDetailResource($produk);
            }

            return new BarangResource($produk);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($produk)
    {
        $produk = Barang::where('uuid', $produk)->firstOrFail();

        return view('member.barang.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BarangEditRequest $request, Barang $produk)
    {
        DB::beginTransaction();
        try {
            Barang::find($produk->id)->update($request->only(['kategori_id', 'barcode', 'produk', 'keterangan',  'stok_warning', 'foto', 'unit_id', 'is_app']));
            DB::commit();

            return redirect()->back()->with('pesan', '<div class="alert alert-success">Data berhasil diperbaruhi</div>');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());

            return redirect()->back()->with('pesan', '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($produk)
    {
        DB::beginTransaction();
        try {
            $produk = Barang::where('uuid', $produk)->firstOrFail();
            $produk->delete();
            // $status = $produk->status;

            // if ($status == true) {
            //     Produk::find($produk->id)->update(['status' => false]);
            // } else {
            //     Produk::find($produk->id)->update(['status' => true]);
            // }
            
            DB::commit();

            return response()->json([
                'pesan' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return response()->json([
                'pesan' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    public function showById($id)
    {
        try {
            $produk = Barang::where('id', $id)->firstOrFail();
            return new BarangResource($produk);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    public function cariBarang(Request $request)
    {
        $key = $request->key;
        $qty = $request->qty;

        try {
            $produk = Barang::query()
                ->where('barcode', $key)
                ->where('stok', '>', 0)
                ->firstOrFail();

            if ($produk->stok < $qty) {
                return new errorResource(['message' => 'Stok tidak mencukupi']);
            }

            return new BarangResource($produk);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource(['message' => 'Produk tidak tersedia', 'status' => 404]);
        }
    }
}
