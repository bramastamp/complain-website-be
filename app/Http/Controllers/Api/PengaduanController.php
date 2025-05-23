<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        return Pengaduan::with(['kategori', 'tanggapans'])->where(function ($q) {
            $q->where('is_anonymous', true)
              ->orWhere('user_id', Auth::id());
        })->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'kategori_id' => 'required|exists:kategori_pengaduans,id',
            'is_anonymous' => 'boolean',
        ]);

        $pengaduan = Pengaduan::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori_id' => $request->kategori_id,
            'is_anonymous' => $request->is_anonymous,
            'user_id' => $request->is_anonymous ? null : Auth::id(),
        ]);

        return response()->json($pengaduan, 201);
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with(['kategori', 'tanggapans'])->findOrFail($id);
        return response()->json($pengaduan);
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if ($pengaduan->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengaduan->update($request->only('judul', 'isi', 'kategori_id'));
        return response()->json($pengaduan);
    }

    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        if ($pengaduan->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengaduan->delete();
        return response()->json(['message' => 'Pengaduan deleted']);
    }
}
