<?php
namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    // Mengambil semua fasilitas untuk acara tertentu
    public function index($acaraId)
    {
        $fasilitas = Fasilitas::where('acara_id', $acaraId)->get();
        return response()->json($fasilitas);
    }

    // Menambahkan fasilitas untuk acara tertentu
    public function store(Request $request, $acaraId)
    {
        $request->validate([
            'fasilitas' => 'required|string|max:255',
        ]);

        $fasilitas = Fasilitas::create([
            'acara_id' => $acaraId,
            'fasilitas' => $request->input('fasilitas'),
        ]);

        return response()->json($fasilitas, 201);
    }


    // Menghapus fasilitas tertentu
    public function destroy(Fasilitas $fasilitas)
    {
        $fasilitas->delete();
        return response()->json(['terhapus']);
    }
}
