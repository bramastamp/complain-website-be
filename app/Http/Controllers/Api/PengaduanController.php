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
        
    public function show($id)
    {
        return Pengaduan::with(['user', 'kategori', 'tanggapans'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'isi' => 'required|string',
            'kategori_id' => 'required|exists:kategori_pengaduans,id',
            'is_anonymous' => 'boolean',
        ]);

        $pengaduan = Pengaduan::create([
            'user_id' => $request->user()->id,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori_id' => $request->kategori_id,
            'is_anonymous' => $request->is_anonymous ?? false,
        ]);

        return response()->json($pengaduan, 201);
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:terkirim,diproses,ditanggapi,selesai']);

        $pengaduan = Pengaduan::findOrFail($id);
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengaduan->status = $request->status;
        $pengaduan->save();

        return response()->json(['message' => 'Status updated']);
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

    public function myPengaduan()
    {
        return Pengaduan::with(['kategori', 'tanggapans'])
            ->where('user_id', Auth::id())
            ->get();
    }

}
