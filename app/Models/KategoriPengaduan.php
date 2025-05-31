<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengaduan extends Model
{
    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'kategori_id');
    }

    public function getNamaAttribute()
    {
        return $this->nama_kategori;
    }
}
