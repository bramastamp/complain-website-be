<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPengaduan::class, 'kategori_id');
    }

    public function tanggapans()
    {
        return $this->hasMany(Tanggapan::class);
    }
}
