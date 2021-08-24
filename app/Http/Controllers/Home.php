<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//import lib session
use Illuminate\Support\Facades\Session;

//import lin JWT
use \Firebase\JWT\JWT;

//import model suplier
use App\M_Suplier;

//import model pengadaan
use App\M_pengadaan;

class Home extends Controller
{
    //


    public function index(){
    	$key = env('APP_KEY');

	    $token = Session::get('token');

	    $tokenDb = M_Suplier::where('token', $token) ->count();

	    if ($tokenDb > 0) {
	    	$data['token'] = $token;
	    }else{
	    	$data['token'] = "kosong";
	    }

	    $data['pengadaan'] = M_Pengadaan::where('status', '1')->paginate(15);
    	return view('home.home',$data);
    }
}
