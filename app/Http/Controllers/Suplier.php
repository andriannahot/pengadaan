<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//import lib session
use Illuminate\Support\Facades\Session;

//import lin JWT
use \Firebase\JWT\JWT;

//import lib response
use Illuminate\Http\Response;

//import lib validator
use Illuminate\Support\Facades\Validator;

//import lib ekripsi
use Illuminate\Contracts\Encryption\DecryptExeption;

//import model suplier
use App\M_Suplier;

//import model admin
use App\M_Admin;



class Suplier extends Controller
{
    //
    public function index(){
    	return view('suplier.login');
    }
    public function masukSuplier(Request $request){

    	$this->validate($request,
    		[
    			'email' => 'required',

    			'password' => 'required'
    		]
    	);

    	$cek =M_Suplier::where('email',$request->email)-> count();
    	$sup =M_Suplier::where('email',$request->email)-> get();

    	if ($cek > 0) {
    		foreach ($sup as $s) {
    			if(decrypt($s->password) == $request ->password) {
    				$key = env('APP_KEY');
    				$data = array(
    					"id_suplier" => $s->id_suplier);

    				$jwt = JWT::encode($data,$key);

    				M_Suplier::where('id_suplier',$s->id_suplier)->update(
    					[
    						'token' =>$jwt
    					]
    				);

    					Session::put('token',$jwt);
    					return redirect('/listSuplier');

    			}else{
    				return redirect('/masukSuplier') ->with('gagal','Password Anda Salah');
    			}
    		}
    	}else{
    		return redirect('/masukSuplier') ->with('gagal','Data Email Tidak Terdaftar');
    	}

    }
    public function suplierKeluar(){

    	 $token = Session::get('token');
    	 if (M_Suplier::where('token',$token)->update(
    	 		[
    	 			'token' => 'keluar',
    	 		]
    	 )) {
    	 	Session::put('token',"");
    	 	return redirect ('/');
    	 }else{
    	 	return redirect('/masukSuplier')->with('gagal','Anda Gagal Login');
    	 }
    }

     public function listSup(){
        $token = Session::get('token');

        $tokenDb = M_Admin::where('token',$token)->count();

        if ($tokenDb > 0) {
            $data['adm'] = M_Admin::where('token',$token)->first();
            $data['suplier'] = M_Suplier::paginate(15);

            return view('admin.listSup',$data);

        }else{
            return redirect('/masukAdmin')->with('gagal','Anda sudah logout, Silahkan Login kembali');
        }
    }
       public function nonAktif($id){
        $token = Session::get('token');

        $tokenDb = M_Admin::where('token',$token)->count();

        if ($tokenDb > 0) {
            if (M_Suplier::where('id_suplier',$id)->update( 

                [
                    "status" => "0"
                ]))
            {
                return redirect('/listSup')->with('berhasil','Data berhasil Di Updated');
                
            }else{
                   return redirect('/listSup')->with('gagal','Data gagal Di Updated');

            }

        }else{
         return redirect('/masukAdmin')->with('gagal','Anda sudah logout, Silahkan Login kembali');
        }
    }

    
    public function Aktif($id){
        $token = Session::get('token');

        $tokenDb = M_Admin::where('token',$token)->count();

        if ($tokenDb > 0) {
            if (M_Suplier::where('id_suplier',$id)->update( 

                [
                    "status" => "1"
                ]))
            {
                return redirect('/listSup')->with('berhasil','Data berhasil Di Updated');
                
            }else{
                   return redirect('/listSup')->with('gagal','Data gagal Di Updated');

            }

        }else{
         return redirect('/masukAdmin')->with('gagal','Anda sudah logout, Silahkan Login kembali');
        }
    }

    public function ubahPassword(Request $request){

        $token = Session::get('token');
        $tokenDb = M_Suplier::where('token',$token)->count();

        if ($tokenDb > 0) {
            $key = env('APP_KEY');

            $sup = M_Suplier::where('token',$token)->first();

            $decode = JWT::decode($token, $key, array('HS256'));
            $decode_array = (array) $decode;

            if (decrypt($sup->password) == $request->passwordlama) {

                if (M_Suplier::where('id_suplier',$decode_array['id_suplier'])->update(
                [
                    "password" => encrypt($request->password)
                ]
            )){
                return redirect('/masukSuplier')->with('gagal','Password Berhasil Di Updated');
            }else{
                return redirect('/masukSuplier')->with('gagal','Password gagal Di Updated');
            }
    }else{
        return redirect('/listSuplier')->with('gagal','Password Gagal Di Update dan Password lama Tidak Sama'); 
        }
    }
}
}