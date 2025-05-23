<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model
{
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
