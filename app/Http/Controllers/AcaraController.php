<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use Illuminate\Http\Request;
use App\Models\Pendaftar;

use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $acaras = Acara::with(['materis', 'fasilitas','pemateriacara'])->get();
        return response()->json($acaras, 200);
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date',
            'tgl_ditutup' => 'nullable|date',
            'lokasi' => 'required|string|max:255',
            'status' => 'required',
            'harga_early' =>'required',
            'harga_reguler'=>'required',
            'tgl_early'=>'required',
            'wa_link'=>'required'
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('gambar_acara', 'public');
        } else {
            $gambarPath = null;
        }

        $acara = Acara::create([
            'nama_acara' => $request->nama_acara,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_akhir' => $request->tgl_akhir,
            'tgl_ditutup' => $request->tgl_ditutup,
            'lokasi' => $request->lokasi,
            'status' => $request->status,
            'harga_early' => $request->harga_early,
            'harga_reguler' => $request->harga_reguler,
            'tgl_early' => $request->tgl_early,
            'wa_link' => $request->wa_link,

        ]);

        return response()->json(['message' => 'Event created successfully', 'acara' => $acara], 201);
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $acara = Acara::with(['materis', 'fasilitas','pemateriacara'])->find($id);
    
        if (!$acara) {
            return response()->json([
                'status' => 'error',
                'message' => 'Acara tidak ada',
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $acara,
        ], 200);
    }
    
    
    /**
     * Update the specified event in storage.
     */
    public function activate($id)
    {
        $acara = Acara::find($id);

        if (!$acara) {
            return response()->json(['message' => 'Acara tidak ditemukan'], 404);
        }

        $acara->activate();

        return response()->json(['message' => 'Acara berhasil diaktifkan', 'acara' => $acara], 200);
    }

    public function deactivate($id)
    {
        $acara = Acara::find($id);

        if (!$acara) {
            return response()->json(['message' => 'Acara tidak ditemukan'], 404);
        }

        $acara->deactivate();

        return response()->json(['message' => 'Acara berhasil dinonaktifkan', 'acara' => $acara], 200);
    }

    public function update(Request $request, Acara $acara)
    {
        $request->validate([
            'nama_acara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date',
            'tgl_ditutup' => 'nullable|date',
            'lokasi' => 'required|string|max:255',
            'status' => 'required',
            'harga_early' =>'required',
            'harga_reguler'=>'required',
            'tgl_early'=>'required',
            'wa_link' => 'required',

        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($acara->gambar) {
                Storage::disk('public')->delete($acara->gambar);
            }

            $gambarPath = $request->file('gambar')->store('gambar_acara', 'public');
        } else {
            $gambarPath = $acara->gambar;
        }

        $acara->update([
            'nama_acara' => $request->nama_acara,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_akhir' => $request->tgl_akhir,
            'tgl_ditutup' => $request->tgl_ditutup,
            'lokasi' => $request->lokasi,
            'status' => $request->status,
            'harga_early' => $request->harga_early,
            'harga_reguler' => $request->harga_reguler,
            'tgl_early' => $request->tgl_early,
            'wa_link' => $request->wa_link,
        ]);

        return response()->json(['message' => 'Event updated successfully', 'acara' => $acara], 200);
    }


    /**
     * Remove the specified event from storage.
     */
    public function destroy(Acara $acara)
    {
        // Hapus gambar dari storage
        if ($acara->gambar) {
            Storage::disk('public')->delete($acara->gambar);
        }

        $acara->delete();
        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
    public function pendapatanByAcara($acaraId)
    {
        $totalPendapatan = Pendaftar::where('acara_id', $acaraId)->sum('jumlah_bayar');

        return response()->json([
            'acara_id' => $acaraId,
            'total_pendapatan' => $totalPendapatan
        ], 200);
    }

}
