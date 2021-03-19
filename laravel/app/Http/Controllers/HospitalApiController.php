<?php

namespace App\Http\Controllers;

use Validator;
use App\Hospital;
use Illuminate\Http\Request;

class HospitalApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hospital = Hospital::all()->toJson(JSON_PRETTY_PRINT);
        return response($hospital, 200);
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
            'nama_rs'        => 'required|min:4|max:100',
            'alamat_rs'      => 'required',
            'website_rs'     => 'required',
            'no_hp'          =>  'required|min:6',
        ]);
        if ($validateData->fails()){
            return response($validateData->errors(), 400);
        } else {
        $hospital = new Hospital();
        $hospital->nama_rs = $request->nama_rs;
        $hospital->alamat_rs = $request->alamat_rs;
        $hospital->website_rs = $request->website_rs;
        $hospital->no_hp = $request->no_hp;
        $hospital->save();
        return response()->json([
            'message' => "Rumah sakit berhasil ditambahkan"], 201);
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
        if(Hospital::where('id', $id)->exists()){
            $hospital = Hospital::find($id)->toJson(JSON_PRETTY_PRINT);
            return response($hospital, 200);
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
        if (Hospital::where('id', $id)->exists()) {
            $validateData = Validator::make($request->all(),[
                'nama_rs'        => 'required|min:4|max:100',
                'alamat_rs'      => 'required',
                'website_rs'     => 'required',
                'no_hp'          =>  'required|min:6',
            ]);
            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $hospital = Hospital::find($id);
                $hospital->nama_rs = $request->nama_rs;
                $hospital->alamat_rs = $request->alamat_rs;
                $hospital->website_rs = $request->website_rs;
                $hospital->no_hp = $request->no_hp;
                $hospital->save();
                return response()->json([
                    'message' => "Data rumah sakit berhasil diubah"], 201);
                }
            } else {
                return response()->json([
                    "message" => "Rumah sakit tidak ditemukan"], 404);
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
        if(Hospital::where('id', $id)->exists()){
            $hospital = Hospital::find($id);
            $hospital->delete();
            return response()->json([
                "message" =>"Rumah sakit berhasil dihapus"], 201);
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"], 404);
        }
    }
}
