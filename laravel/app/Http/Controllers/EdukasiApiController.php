<?php

namespace App\Http\Controllers;

use File;
use Validator;
use App\Edukasi;
use Illuminate\Http\Request;

class EdukasiApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $edukasi = Edukasi::all()->toJson(JSON_PRETTY_PRINT);
        return response($edukasi, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'judul_edukasi'         => 'required|min:4|max:100',
            'deskripsi_edukasi'     => 'required',
            'gambar_edukasi'        =>  'required|file|image|max:1000',
        ]);
        if ($validateData->fails()){
            return response($validateData->errors(), 400);
        } else {
            $edukasi = new Edukasi();
            $edukasi->judul_edukasi = $request->judul_edukasi;
            $edukasi->deskripsi_edukasi = $request->deskripsi_edukasi;
            if($request->hasFile('gambar_edukasi')){
                $extFile = $request->gambar_edukasi->getClientOriginalExtension();
                $namaFile = 'user-'.time().".".$extFile;
                $path = $request->gambar_edukasi->move('assets/edukasi',$namaFile);
                $edukasi->gambar_edukasi = $path;
            }
            $edukasi->save();
            return response()->json([
                "message" => "Edukasi telah ditambahkan"], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Edukasi::where('id', $id)->exists()){
            $edukasi = Edukasi::find($id)->toJson(JSON_PRETTY_PRINT);
            return response($edukasi, 200);
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Edukasi::where('id', $id)->exists()){
            $validateData = Validator::make($request->all(), [
                'judul_edukasi'         => 'required|min:4|max:100',
                'deskripsi_edukasi'     => 'required',
                'gambar_edukasi'        =>  'required|file|image|max:1000',
            ]);

            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $edukasi = Edukasi::find($id);
                $edukasi->judul_edukasi = $request->judul_edukasi;
                $edukasi->deskripsi_edukasi = $request->deskripsi_edukasi;
                if($request->hasFile('gambar_edukasi')){
                    $extFile = $request->gambar_edukasi->getClientOriginalExtension();
                    $namaFile = 'user-'.time().".".$extFile;
                    File::delete($edukasi->gambar_edukasi);
                    $path = $request->gambar_edukasi->move('assets/edukasi',$namaFile);
                    $edukasi->gambar_edukasi = $path;
                }
                $edukasi->save();
                return response()->json([
                    "message" => "Edukai telah diubah"], 201);
            }
        } else{
            return response()->json([
                "message" => "Data tidak ditemukan"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Edukasi::where('id', $id)->exists()){
            $edukasi = Edukasi::find($id);
            File::delete($edukasi->gambar_edukasi);
            $edukasi->delete();
            return response()->json([
                "message" =>"Data Edukasi berhasil dihapus"], 201);
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"], 404);
        }
    }
}
