@extends('member.layouts.layout')

@section('konten')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Edit produk</h5>
                    <div class="card-body">
                        @if (session()->has('pesan'))
                            {!! session('pesan') !!}
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('barang.update', ['produk' => $produk->id]) }}" method="POST"
                            enctype="multipart/form-data" id="my-form">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="id" value="{{ $produk->id }}">

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">kode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="barcode"
                                        value="{{ $produk->barcode }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">kategori</label>
                                <div class="col-sm-7">
                                    <select name="kategori_id" id="kategori" class="form-control kategori-select"
                                        data-ajax--url="{{ route('drop-kategori') }}">
                                        @if ($produk->kategori_id)
                                            <option value="{{ $produk->kategori_id }}">{{ $produk->kategori->kategori }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    @include('member.layouts.modalKategori')
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">produk</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="produk" value="{{ $produk->produk }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">keterangan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="keterangan"
                                        value="{{ $produk->keterangan }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">stok warning</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="stok_warning"
                                        value="{{ $produk->stok_warning }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">harga</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="harga" value="{{ $produk->harga }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">unit</label>
                                <div class="col-sm-9">
                                    <select name="unit_id" id="unit" class="form-control unit-select"
                                        data-ajax--url="{{ route('drop-unit') }}">
                                        @if ($produk->unit_id)
                                            <option value="{{ $produk->unit_id }}">{{ $produk->unit->unit }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">is App</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input btn-check" type="checkbox" role="switch"
                                            id="flexSwitchCheckDefault" name="is_app"
                                            {{ $produk->is_app ? 'checked' : '' }}>
                                    </div>
                                    <small>
                                        Pengaturan untuk menampilkan atau menyembunyikan produk di Aplikasi
                                    </small>
                                </div>
                            </div>


                            @include('member.layouts.uploadfoto', [
                                'path' => 'produk',
                                'foto' => $produk->foto,
                            ])

                            <div class="row ">
                                <div class="col-sm-12">
                                    <a href="{{ route('barang.index') }}" class="btn btn-link btn-sm">Kembali</a>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Barang\BarangEditRequest', '#my-form') !!}
@endsection
