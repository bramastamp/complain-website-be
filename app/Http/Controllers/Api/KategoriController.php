<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriPengaduan;

class KategoriController extends Controller
{
    public function index()
    {
        return KategoriPengaduan::all();
    }
}
