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
        return Pengaduan::with(['user', 'kategori', 'tanggapans'])->where(function ($q) {
            $q->where('is_anonymous', true)
              ->orWhere('user_id', Auth::id());
            })->get();
    }
        
    public function show($id)
    {
        $pengaduan = Pengaduan::with('user')->find($id);

        if (!$pengaduan) {
            return response()->json(['message' => 'Pengaduan tidak ditemukan'], 404);
        }

        return response()->json($pengaduan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'isi' => 'required|string',
            'kategori_id' => 'required|exists:kategori_pengaduans,id',
            'is_anonymous' => 'boolean',
        ]);

        $pengaduan = new Pengaduan([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori_id' => $request->kategori_id,
            'is_anonymous' => $request->is_anonymous ?? false,
        ]);

        if (!$pengaduan->is_anonymous && $request->user()) {
            $pengaduan->user_id = $request->user()->id;
        }

        $pengaduan->save();
    }

    public function storeAsGuest(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'isi' => 'required|string',
            'kategori_id' => 'required|exists:kategori_pengaduans,id',
        ]);

        $pengaduan = Pengaduan::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori_id' => $request->kategori_id,
            'is_anonymous' => true,
            'user_id' => null, // benar-benar anonim
        ]);

        return response()->json($pengaduan, 201);
    }

    public function update(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        // Hanya pemilik (user) yang bisa update, bukan admin
        if (auth()->user()->role === 'admin' || auth()->id() !== $pengaduan->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengaduan->update($request->all());

        return response()->json(['message' => 'Pengaduan berhasil diupdate']);
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

    public function all()
    {
        $pengaduans = Pengaduan::with('kategori', 'user')->latest()->get();
        return response()->json($pengaduans);
    }

    public function myPengaduan()
    {
        return Pengaduan::with(['kategori', 'tanggapans'])
            ->where('user_id', Auth::id())
            ->get();
    }
}