<?php

namespace App\Http\Controllers;

use App\Models\Pemateri;
use Illuminate\Http\Request;

class PemateriController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $pemateris = Pemateri::all();
        return response()->json($pemateris);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'pemateri' => 'required|string|max:255',
        ]);

        $pemateri = Pemateri::create($request->all());
        return response()->json($pemateri, 201);
    }

    // Display the specified resource.
    public function show($id)
    {
        $pemateri = Pemateri::find($id);

        if (!$pemateri) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($pemateri);
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $request->validate([
            'pemateri' => 'required|string|max:255',
        ]);

        $pemateri = Pemateri::find($id);

        if (!$pemateri) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $pemateri->update($request->all());
        return response()->json($pemateri);
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $pemateri = Pemateri::find($id);

        if (!$pemateri) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $pemateri->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }
}
