<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tanggapan;
use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function store(Request $request, $pengaduanId)
    {
        $request->validate(['isi_tanggapan' => 'required|string']);

        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tanggapan = Tanggapan::create([
            'pengaduan_id' => $pengaduanId,
            'admin_id' => $request->user()->id,
            'isi_tanggapan' => $request->isi_tanggapan,
        ]);

        return response()->json($tanggapan, 201);
    }
}
