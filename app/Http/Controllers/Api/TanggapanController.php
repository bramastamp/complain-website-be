<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tanggapan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tanggapan::with('pengaduan');

        if ($request->has('pengaduan_id')) {
            $query->where('pengaduan_id', $request->input('pengaduan_id'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengaduan_id' => 'required|integer|exists:pengaduans,id',
            'isi_tanggapan' => 'required|string',
        ]);

        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tanggapan = Tanggapan::create([
            'pengaduan_id' => $request->input('pengaduan_id'),
            'admin_id' => $request->user()->id,
            'isi_tanggapan' => $request->input('isi_tanggapan'),
        ]);

        // Update status pengaduan jadi "ditanggapi"
        $pengaduan = Pengaduan::find($request->input('pengaduan_id'));
        if ($pengaduan) {
            $pengaduan->status = 'ditanggapi';  // sesuaikan dengan field dan nilai status yang dipakai
            $pengaduan->save();
        }

        return response()->json($tanggapan, 201);
    }


    public function show($id)
    {
        $tanggapan = Tanggapan::with('pengaduan', 'admin')->find($id);

        if (! $tanggapan) {
            return response()->json(['message' => 'Tanggapan not found'], 404);
        }

        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($tanggapan);
    }

    public function byPengaduan($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        // Batasi agar user hanya bisa melihat pengaduan miliknya
        if (auth()->user()->role === 'user' && $pengaduan->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $tanggapans = Tanggapan::where('pengaduan_id', $id)->get();
        return response()->json($tanggapans);
    }

}
