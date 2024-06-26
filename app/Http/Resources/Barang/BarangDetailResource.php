<?php

namespace App\Http\Resources\Barang;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'kode' => $this->barcode,
            'produk' => $this->produk,
            'keterangan' => $this->keterangan,
            'kategori' => $this->kategori->kategori,
            'stok' => $this->stok,
            'stok warning' => $this->stok_warning,
            'harga' => number_format($this->harga, 0, ',', '.'),
            'unit' => $this->unit->unit,
            'is app' => statusTable($this->is_app, false),
            'status' => statusTable($this->status, false),
            'ditambahkan' => Carbon::parse($this->created_at)->timezone(session('zonawaktu'))->isoFormat('DD MMMM YYYY HH:mm')
        ];
    }
}
