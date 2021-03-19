<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Illuminate\Http\Request;
use Bcrypt\Bcrypt;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all()->toJson(JSON_PRETTY_PRINT);
        return response($user, 200);
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
        $bcrypt = new Bcrypt();
        $validateData = Validator::make($request->all(), [
            'nama_lengkap'  => 'required|min:4|max:100',
            'username'      => 'required|min:4|max:20|unique:users,username',
            'password'      => 'required|min:8',
            'alamat'        =>  'required',
            'no_ktp'        =>  'required|size:8|unique:users,no_ktp',
        ]);
        if ($validateData->fails()){
            return response($validateData->errors(), 400);
        } else {
        $user = new User();
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->password = $bcrypt->encrypt($request->password);
        $user->alamat = $request->alamat;
        $user->no_ktp = $request->no_ktp;
        $user->save();
        return redirect('http://www.simusik.com/HTML/');
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
        if(User::where('id', $id)->exists()){
            $user = User::find($id)->toJson(JSON_PRETTY_PRINT);
            return response($user, 200);
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"], 404);
        }
    }

    public function ceklogin(Request $request){
        $bcrypt = new Bcrypt();
        $validateData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $result = User::where('username', $validateData['username'])->first();
        if ($result){
            if(($bcrypt->verify($request->password, $result->password))){
                session(['token' => base64_encode($request->username . "ini SALT" . $result->password)]);
                return redirect('http://www.simusik.com/HTML/');
            } else {
                //return response($validateData, 400);
                return back() ->withInput()->with('pesan', "Login Gagal");
            }
        } else{
            return back() ->withInput()->with('pesan', "Login Gagal");
        }
    }

    
    public function logout(){
        session()->forget('token');
        return redirect('http://www.simusik.com')->with('pesan', "Logout berhasil");
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
        if (User::where('id', $id)->exists()) {
            $validateData = Validator::make($request->all(),[
                'nama_lengkap'  => 'required|min:4|max:100',
                'password'      => 'required|min:8',
                'alamat'        =>  'required',
            ]);
            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $user = User::find($id);
                $user->nama_lengkap = $request->nama_lengkap;
                //$user->username = $request->username;
                $user->password = $request->password;
                $user->alamat = $request->alamat;
                //$user->no_ktp = $request->no_ktp;
                $user->save();
                return response()->json([
                    'message' => "Data user berhasil diubah"], 201);
                }
            } else {
                return response()->json([
                    "message" => "User tidak ditemukan"], 404);
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
        if(User::where('id', $id)->exists()){
            $user = User::find($id);
            $user->delete();
            return response()->json([
                "message" =>"User berhasil dihapus"], 201);
        } else {
            return response()->json([
                "message" => "Data tidak ditemukan"], 404);
        }
    }
}
