<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//import lib fungsi validasi laravel
use Illuminate\Support\Facades\Validator;

//import model suplier
use App\M_Suplier;

//import lin response
use Illuminate\Http\Response;

//import lib encrypt
use Illuminate\Contracts\Encryption\DecryptExeption;

class Registrasi extends Controller
{
    //
	public function index(){
		return view('registrasi.registrasi');
	}

    public function registrasi(Request $request){

    	$this->validate($request,
    		[
    			'nama_usaha' => 'required',

    			'email' => 'required',

    			'alamat' => 'required',

    			'no_npwp' => 'required',

    			'password' => 'required'
    		]
    	);
    	if (
    		M_Suplier::create(
    			[
    				"nama_usaha" => $request->nama_usaha,

    				"email" => $request ->email,

    				"alamat" => $request ->alamat,

    				"no_npwp" => $request ->no_npwp,

    				"password" => encrypt($request->password)

    			]
    		)
    	) {
    		return redirect('/registrasi')->with('berhasil','data berhasil disimpan');
    	}else{
    		return redirect('/registrasi')->with('gagal','data gagal disimpan');
    	}
    }
}
