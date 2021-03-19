<?php

namespace App\Http\Controllers;

use File;
use Validator;
use App\Tulisan;
use Illuminate\Http\Request;

class TulisanApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tulisan = Tulisan::all()->toJson(JSON_PRETTY_PRINT);
        return response($tulisan, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'judul_tulisan'         => 'required|min:4|max:100',
            'deskripsi_tulisan'     => 'required',
            'gambar_tulisan'        =>  'required|file|image|max:1000',
        ]);
        if ($validateData->fails()){
            return response($validateData->errors(), 400);
        } else {
            $tulisan = new Tulisan();
            $tulisan->judul_tulisan = $request->judul_tulisan;
            $tulisan->deskripsi_tulisan = $request->deskripsi_tulisan;
            if($request->hasFile('gambar_tulisan')){
                $extFile = $request->gambar_tulisan->getClientOriginalExtension();
                $namaFile = 'user-'.time().".".$extFile;
                $path = $request->gambar_tulisan->move('assets/images',$namaFile);
                $tulisan->gambar_tulisan = $path;
            }
            $tulisan->save();
            return response()->json([
                "message" => "Tulisan telah ditambahkan"], 201);
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
        if(Tulisan::where('id', $id)->exists()){
            $tulisan = Tulisan::find($id)->toJson(JSON_PRETTY_PRINT);
            return response($tulisan, 200);
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
        if(Tulisan::where('id', $id)->exists()){
            $validateData = Validator::make($request->all(), [
                'judul_tulisan'         => 'required|min:4|max:100',
                'deskripsi_tulisan'     => 'required',
                'gambar_tulisan'        =>  'required|file|image|max:1000',
            ]);

            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $tulisan = Tulisan::find($id);
                $tulisan->judul_tulisan = $request->judul_tulisan;
                $tulisan->deskripsi_tulisan = $request->deskripsi_tulisan;
                if($request->hasFile('gambar_tulisan')){
                    $extFile = $request->gambar_tulisan->getClientOriginalExtension();
                    $namaFile = 'user-'.time().".".$extFile;
                    File::delete($tulisan->gambar_tulisan);
                    $path = $request->gambar_tulisan->move('assets/images',$namaFile);
                    $tulisan->gambar_tulisan = $path;
                }
                $tulisan->save();
                return response()->json([
                    "message" => "Tulisan telah diubah"], 201);
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
        if(Tulisan::where('id', $id)->exists()){
            $tulisan = Tulisan::find($id);
            File::delete($tulisan->gambar_tulisan);
            $tulisan->delete();
            return response()->json([
                "message" =>"Data Tulisan berhasil dihapus"], 201);
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"], 404);
        }
    }
}
