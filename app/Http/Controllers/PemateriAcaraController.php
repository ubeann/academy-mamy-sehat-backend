<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use Illuminate\Http\Request;
use App\Models\PemateriAcara;

class PemateriAcaraController extends Controller
{
    // Display a listing of the resource.
    public function index($acaraId)
    {
        // Ambil acara berdasarkan ID
        $acara = Acara::find($acaraId);
    
        if (!$acara) {
            return response()->json([
                'status' => 'error',
                'message' => 'Acara tidak ditemukan',
            ], 404);
        }
    
        // Ambil semua PemateriAcara yang terkait dengan acara tersebut
        $pemateri_acaras = PemateriAcara::where('acara_id', $acaraId)
            ->with('pemateri') // Muat data pemateri
            ->get();
    
        // Buat array dari pemateri yang terkait, termasuk id PemateriAcara
        $pemateris = $pemateri_acaras->map(function ($pemateri_acara) {
            return [
                'id' => $pemateri_acara->id, // ID dari PemateriAcara
                'pemateri_id' => $pemateri_acara->pemateri->id,
                'pemateri' => $pemateri_acara->pemateri->pemateri,
                'created_at' => $pemateri_acara->pemateri->created_at,
                'updated_at' => $pemateri_acara->pemateri->updated_at,
            ];
        });
    
        return response()->json([
            'acara' => $acara,
            'pemateris' => $pemateris
        ]);
    }
    
    
    

    // Show the form for creating a new resource.
    public function create()
    {
        // Implement this if you need a form for creating a new resource
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'acara_id' => 'required|exists:acaras,id',
            'pemateri_id' => 'required|exists:pemateris,id',
        ]);
    
        // Cek apakah pemateri sudah terdaftar di acara yang sama
        $existingPemateriAcara = PemateriAcara::where('acara_id', $request->acara_id)
            ->where('pemateri_id', $request->pemateri_id)
            ->first();
    
        if ($existingPemateriAcara) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemateri sudah terdaftar dalam acara ini.'
            ], 409); // Conflict
        }
    
        // Jika tidak ada, buat entri baru
        $pemateri_acara = PemateriAcara::create($request->all());
    
        return response()->json($pemateri_acara, 201);
    }
    

    // Display the specified resource.


    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $pemateri_acara = PemateriAcara::find($id);

        if (!$pemateri_acara) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $pemateri_acara->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }
}
