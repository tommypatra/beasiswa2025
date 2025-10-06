<?php

namespace App\Http\Controllers;

use App\Models\PesertaUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PesertaUjian $pesertaUjian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PesertaUjian $pesertaUjian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PesertaUjian $pesertaUjian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PesertaUjian $pesertaUjian)
    {
        //
    }

    public function hapusPesertaUjian(string $id)
    {
        try {
            DB::beginTransaction();
            PesertaUjian::where('beasiswa_id', $id)->delete();
            DB::commit();
            return response()->json(null, 204);
            // return response()->json(['status' => true, 'message' => 'hapus data berhasil dilakukan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage(), 'data' => null], 500);
        }
    }
}
