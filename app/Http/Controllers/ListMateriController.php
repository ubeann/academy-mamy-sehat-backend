<?php
namespace App\Http\Controllers;

use App\Models\ListMateri;
use Illuminate\Http\Request;

class ListMateriController extends Controller
{
    // Mengambil semua materi untuk acara tertentu
    public function index($acaraId)
    {
        $materis = ListMateri::where('acara_id', $acaraId)->get();
        return response()->json($materis);
    }

    // Menambahkan materi untuk acara tertentu
    public function store(Request $request, $acaraId)
    {
        $request->validate([
            'materi' => 'required|string',
        ]);

        $materi = ListMateri::create([
            'acara_id' => $acaraId,
            'materi' => $request->input('materi'),
        ]);

        return response()->json($materi, 201);
    }

    // Menampilkan materi tertentu
    public function show(ListMateri $listMateri)
    {
        return response()->json($listMateri);
    }

    // Mengupdate materi tertentu
    public function update(Request $request, ListMateri $listMateri)
    {
        $request->validate([
            'materi' => 'required|string',
        ]);

        $listMateri->update([
            'materi' => $request->input('materi'),
        ]);

        return response()->json($listMateri);
    }

    // Menghapus materi tertentup
    public function destroy(ListMateri $listMateri)
    {
        $listMateri->delete();
        return response()->json('Materi Terhapus');
    }
}
