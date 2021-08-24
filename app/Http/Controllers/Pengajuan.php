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

use Illuminate\Support\Facades\Storage;

//import Model Admin
use App\M_Admin;

use App\M_Pengajuan;

use App\M_Suplier;

use App\M_Pengadaan;

use App\M_Laporan;

class Pengajuan extends Controller
{
    //
    public function pengajuan(){

    		$key = env('APP_KEY');

	    $token = Session::get('token');

	    $tokenDb = M_Admin::where('token', $token) ->count();

	    if ($tokenDb > 0) {

            $pengajuan = M_Pengajuan::where('status','1')->paginate(15);

            $dataP = array();
            foreach ($pengajuan as $p) {
                $pengadaan = M_Pengadaan::where('id_pengadaan', $p->id_pengadaan)->first();
                $sup = M_Suplier::where('id_suplier', $p->id_suplier)->first();
                $dataP[] = array(
                    "id_pengajuan" => $p->id_pengajuan,
                    "nama_pengadaan" => $pengadaan->nama_pengadaan,
                    "gambar" => $pengadaan->gambar,
                    "anggaran" => $pengadaan->anggaran,
                    "proposal" => $p->proposal,
                    "anggaran_pengajuan" => $p->anggaran,
                    "status_pengajuan" => $p->status,
                    "nama_suplier" => $sup->nama_usaha,
                    "email_suplier" => $sup->email,
                    "alamat_suplier" => $sup->alamat,
                );
            }

             $data['adm'] = M_Admin::where('token',$token)->first();

            $data['pengajuan'] = $dataP;

	    	return view('pengajuan.list',$data);

	    }else{
	    	return redirect('/masukAdmin') ->with('gagal','Anda Silahkan Login dahulu');
      
		}
	}

	//function tambah pengajuan

	 public function tambahPengajuan(Request $request){

	 	$key = env('APP_KEY');
        
        $token = Session::get('token');

        $tokenDb = M_Suplier::where('token',$token)->count();

        $decode = JWT::decode($token, $key,array('HS256'));

        $decode_array = (array) $decode;

        if ($tokenDb > 0){
            $this->validate($request,
            [   
                'id_pengadaan' => 'required',

                'proposal' => 'required|mimes:pdf|max:10000',

                'anggaran' => 'required'
            ]
        );
            $cekPengajuan = M_Pengajuan::where('id_suplier',$decode_array['id_suplier'])->where('id_pengadaan',$request->id_pengadaan)->count();

            if($cekPengajuan == 0){

            	 $path = $request->file('proposal')->store('public/proposal');
          
          if (M_Pengajuan::create(
                [
                    "id_pengadaan" => $request->id_pengadaan,

                    "id_suplier" => $decode_array['id_suplier'],

                    "proposal" => $path,

                    "anggaran" =>$request ->anggaran

                ]
            )
        ) {
            return redirect('/listSuplier')->with('berhasil','Data Pengajuan Berhasil');
        }else{
            return redirect('/listSuplier')->with('gagal','Pengajuan Data Gagal, mohon Hubungi admin');
        }

            }else{
            	return redirect('/listSuplier')->with('gagal','Pengajuan Sudah Pernah Dilakukan');

            }

           

   } else {
            return redirect ('/masukSuplier')->with('gagal','Anda Sudah Logout, Silahkan Login kembali');
        }
   }
   
   public function terimaPengajuan($id){

    $token = Session::get('token');
     $tokenDb = M_Admin::where('token', $token) ->count();

         if ($tokenDb > 0){

            if (M_Pengajuan::where('id_pengajuan',$id)->update(
                [
                    "status" => "2"
                ]
            )){
                return redirect('/pengajuan')->with('berhasil','Pengajuan Berhasil');
            }else{
                return redirect('/pengajuan')->with('gagal','Pengajuan Sudah Gagal');

            }

        }else{
            return redirect('/masukAdmin') ->with('gagal','Anda Silahkan Login dahulu');
      

        }

   }

   public function tolakPengajuan($id){

    $token = Session::get('token');
     $tokenDb = M_Admin::where('token', $token) ->count();

         if ($tokenDb > 0){

            if (M_Pengajuan::where('id_pengajuan',$id)->update(
                [
                    "status" => "0"
                ]
            )){
                return redirect('/pengajuan')->with('berhasil','Pengajuan Berhasil');
            }else{
                return redirect('/pengajuan')->with('gagal','Pengajuan Sudah Gagal');

            }

        }else{
            return redirect('/masukAdmin') ->with('gagal','Anda Silahkan Login dahulu');
      

        }

   }

   public function riwayatku(){

        $key = env('APP_KEY');
        
        $token = Session::get('token');

        $tokenDb = M_Suplier::where('token',$token)->count();

        $decode = JWT::decode($token, $key,array('HS256'));

        $decode_array = (array) $decode;

        if ($tokenDb > 0){
                
                  $pengajuan = M_Pengajuan::where('id_suplier',$decode_array['id_suplier'])->get();

                  $dataArr = array();
                  foreach ($pengajuan as $p) {
                $pengadaan = M_Pengadaan::where('id_pengadaan', $p->id_pengadaan)->first();
                $sup = M_Suplier::where('id_suplier', $decode_array['id_suplier'])->first();

                $lapCount = M_Laporan::where('id_pengajuan',$p->id_pengajuan)->count(); 
                $lap = M_Laporan::where('id_pengajuan',$p->id_pengajuan)->first();

                if ($lapCount > 0) {
                     $lapLink = $lap->laporan;

                 }else {
                    $lapLink ="-";
                 } 


                $dataArr[] = array(
                    "id_pengajuan" => $p->id_pengajuan,
                    "nama_pengadaan" => $pengadaan->nama_pengadaan,
                    "gambar" => $pengadaan->gambar,
                    "anggaran" => $pengadaan->anggaran,
                    "proposal" => $p->proposal,
                    "anggaran_pengajuan" => $p->anggaran,
                    "status_pengajuan" => $p->status,
                    "nama_suplier" => $sup->nama_usaha,
                    "email_suplier" => $sup->email,
                    "alamat_suplier" => $sup->alamat,
                    "laporan" => $lapLink
                );
                    
                  }

                  $data['sup'] = M_Suplier::where('token',$token)->first();

                  $data['pengajuan'] = $dataArr;

                  return view('suplier.riwayat_pengajuan',$data);

        }else{
            return redirect('/listSuplier')->with('gagal','Pengajuan Sudah Pernah Dilakukan');


        }
   }

    public function tambahLaporan(Request $request){

        $key = env('APP_KEY');
        
        $token = Session::get('token');

        $tokenDb = M_Suplier::where('token',$token)->count();

        $decode = JWT::decode($token, $key,array('HS256'));

        $decode_array = (array) $decode;

        if ($tokenDb > 0){
            $this->validate($request,
            [   
                'id_pengajuan' => 'required',

                'laporan' => 'required|mimes:pdf|max:10000',

            ]
        );
            $cekLaporan = M_Laporan::where('id_suplier',$decode_array['id_suplier'])->where('id_pengajuan',$request->id_pengajuan)->count();

            if($cekLaporan == 0){

                 $path = $request->file('laporan')->store('public/laporan');
          
          if (M_Laporan::create(
                [
                    "id_pengajuan" => $request->id_pengajuan,

                    "id_suplier" => $decode_array['id_suplier'],

                    "laporan" => $path,

                ])) {
            return redirect('/riwayatku')->with('berhasil','Laporan Berhasil di Upload');
        }else{
            return redirect('/riwayatku')->with('gagal','Pengajuan , Laporan Gagal DiUpload');
        }

            }else{
                return redirect('/riwayatku')->with('gagal','Pengajuan Sudah Pernah Dilakukan');

            }

           
   } else {
            return redirect ('/masukSuplier')->with('gagal','Anda Sudah Logout, Silahkan Login kembali');
        }
   }


   public function laporan(){

            $key = env('APP_KEY');

        $token = Session::get('token');

        $tokenDb = M_Admin::where('token', $token) ->count();

        if ($tokenDb > 0) {

            $pengajuan = M_Pengajuan::where('status','2')->paginate(15);

            $dataP = array();
            foreach ($pengajuan as $p) {
                $pengadaan = M_Pengadaan::where('id_pengadaan', $p->id_pengadaan)->first();
                $sup = M_Suplier::where('id_suplier', $p->id_suplier)->first();

                $c_laporan = M_Laporan::where('id_pengajuan',$p->id_pengajuan)->count();
                $laporan = M_Laporan::where('id_pengajuan',$p->id_pengajuan)->first();

                if ($c_laporan > 0) {

                     $dataP[] = array(
                    "id_pengajuan" => $p->id_pengajuan,
                    "nama_pengadaan" => $pengadaan->nama_pengadaan,
                    "gambar" => $pengadaan->gambar,
                    "anggaran" => $pengadaan->anggaran,
                    "proposal" => $p->proposal,
                    "anggaran_pengajuan" => $p->anggaran,
                    "status_pengajuan" => $p->status,
                    "nama_suplier" => $sup->nama_usaha,
                    "email_suplier" => $sup->email,
                    "alamat_suplier" => $sup->alamat,
                    "laporan" => $laporan->laporan,
                    "id_laporan" => $laporan->id_laporan,
                );
                }
               
            }
             $data['adm'] = M_Admin::where('token',$token)->first();
            $data['pengajuan'] = $dataP;

            
            return view('admin.laporan',$data);

        }else{
            return redirect('/masukAdmin') ->with('gagal','Anda Silahkan Login dahulu');
      
        }
    }

  public function selesaiPengajuan($id){

    $token = Session::get('token');
     $tokenDb = M_Admin::where('token', $token) ->count();

         if ($tokenDb > 0){

            if (M_Pengajuan::where('id_pengajuan',$id)->update(
                [
                    "status" => "3"
                ]
            )){
                return redirect('/laporan')->with('berhasil','Pengajuan Selesai');
            }else{
                return redirect('/laporan')->with('gagal','Pengajuan Gagal selesai');

            }

        }else{
            return redirect('/masukAdmin') ->with('gagal','Anda Silahkan Login dahulu');
      

        }

   }
public function pengajuanselesai(){

        $key = env('APP_KEY');
        
        $token = Session::get('token');

        $tokenDb = M_Suplier::where('token',$token)->count();

        $decode = JWT::decode($token, $key,array('HS256'));

        $decode_array = (array) $decode;

        if ($tokenDb > 0){
                
                  $pengajuan = M_Pengajuan::where('id_suplier',$decode_array['id_suplier'])->where('status','3')->get();

                  $dataArr = array();
                  foreach ($pengajuan as $p) {
                $pengadaan = M_Pengadaan::where('id_pengadaan', $p->id_pengadaan)->first();
                $sup = M_Suplier::where('id_suplier', $decode_array['id_suplier'])->first();

                $lapCount = M_Laporan::where('id_pengajuan',$p->id_pengajuan)->count(); 
                $lap = M_Laporan::where('id_pengajuan',$p->id_pengajuan)->first();

                if ($lapCount > 0) {
                     $lapLink = $lap->laporan;

                 }else {
                    $lapLink ="-";
                 } 


                $dataArr[] = array(
                    "id_pengajuan" => $p->id_pengajuan,
                    "nama_pengadaan" => $pengadaan->nama_pengadaan,
                    "gambar" => $pengadaan->gambar,
                    "anggaran" => $pengadaan->anggaran,
                    "proposal" => $p->proposal,
                    "anggaran_pengajuan" => $p->anggaran,
                    "status_pengajuan" => $p->status,
                    "nama_suplier" => $sup->nama_usaha,
                    "email_suplier" => $sup->email,
                    "alamat_suplier" => $sup->alamat,
                    "laporan" => $lapLink
                );
                    
                  }

                  $data['sup'] = M_Suplier::where('token',$token)->first();

                  $data['pengajuan'] = $dataArr;

                  return view('suplier.pengajuanselesai',$data);

        }else{
            return redirect('/listSuplier')->with('gagal','Pengajuan Sudah Pernah Dilakukan');


        }
   }

    public function tolakLaporan($id){
           
            $token = Session::get('token');

            $tokenDb = M_Admin::where('token',$token)->count();

            if ($tokenDb > 0){

            $laporan = M_laporan::where('id_laporan',$id)->count();

            if($laporan > 0){

                $dataLaporan = M_laporan::where('id_laporan',$id)->first();

            if (Storage::delete($dataLaporan->laporan)) {
                if (M_Laporan::where('id_laporan',$id)->delete())
                {
                    return redirect('/laporan')->with('berhasil','Laporan Berhasil Ditolak');
                }
            }else{
                return redirect('/laporan')->with('gagal','data gagal ditolak');
            }
        }else{

            return redirect('/laporan')->with('gagal','data gagal Ditemukan');
        }


            } else {
                return redirect ('/masukAdmin')->with('gagal','Anda Sudah Logout, Silahkan Login kembali');
        }
   }




}
