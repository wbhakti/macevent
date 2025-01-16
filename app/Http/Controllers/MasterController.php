<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MasterController extends Controller
{
    public function slider()
    {
        try{
            if (!session()->has('user_id')) {
                return redirect('/');
            }
            //get all data campaign
            $listSlider = DB::table('slider')->get();

            // Mengirim data ke tampilan
            return view('masterslider', [
                'data' => $listSlider
            ]);
        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            return view('masterslider', ['error' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function postslider(Request $request)
    {
        try{

            if (!session()->has('user_id')) {
                return redirect('/');
            }

            if($request->input('proses') == "save"){
                // Proses upload file
                if ($request->hasFile('img_slider')) {
                    $file = $request->file('img_slider');
                    $fileName = date('YmdHis') . '_' . $file->getClientOriginalName();
                    $file->move('public/img', $fileName);
                } else {
                    return redirect()->back()->with('success', 'Image upload failed.');
                }

                // Simpan data ke database
                DB::table('slider')->insert([
                    'img_slider' => $fileName,
                    'title_slider' => $request->input('title_slider'),
                    'desc_slider' => $request->input('desc_slider'),
                    'action' => $request->input('action_slider'),
                    'is_active' => 'Y',
                ]);

                return redirect()->back()->with('success', 'Save success!');
            }
            else if($request->input('proses') == "edit"){
                // Proses upload file jika ada file baru
                if ($request->hasFile('img_slider')) {
                    $file = $request->file('img_slider');
                    $fileName = date('YmdHis') . '_' . $file->getClientOriginalName();
                    $file->move('public/img', $fileName);
                } else {
                    $fileName = $request->input('old_img_slider');
                }

                // Update data di database
                DB::table('slider')
                    ->where('rowid', $request->input('rowid'))
                    ->update([
                        'img_slider' => $fileName,
                        'title_slider' => $request->input('title_slider'),
                        'desc_slider' => $request->input('desc_slider'),
                        'action' => $request->input('action_slider'),
                        'is_active' => $request->input('is_active'),
                    ]);

                return redirect()->back()->with('success', 'Edit slider success!');
            }
            else{
                //delete
                DB::table('slider')->where('rowid', $request->input('rowid'))->delete();

                return redirect()->back()->with('success', 'Delete slider success!');
            }
        }catch(\Exception $e){
            Log::error('Error occurred : ' . $e->getMessage());
            return redirect()->back()->with('success', 'Save slider failed : ' . $e->getMessage());
        }
    }

    public function event()
    {
        try{
            if (!session()->has('user_id')) {
                return redirect('/');
            }

            $listEvent = DB::table('event')->get();

            // Mengirim data ke tampilan
            return view('masterevent', [
                'event' => $listEvent
            ]);
        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            return view('mastercontent', ['error' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function postevent(Request $request)
    {
        try{

            if (!session()->has('user_id')) {
                return redirect('/');
            }

            if($request->input('proses') == "save"){
                // Proses upload file
                if ($request->hasFile('img_event')) {
                    $file = $request->file('img_event');
                    $fileName = date('YmdHis') . '_' . $file->getClientOriginalName();
                    $file->move('public/img', $fileName);
                } else {
                    return redirect()->back()->with('success', 'Image upload failed.');
                }

                //image SNK
                $fileSnk = $request->file('img_snk');
                $fileNameSnk = date('YmdHis') . '_' . $file->getClientOriginalName();
                $fileSnk->move('public/img', $fileNameSnk);

                // Simpan data ke database
                DB::table('event')->insert([
                    'id_event' => 'event'.date('YmdHis'),
                    'title_event' => $request->input('title_event'),
                    'desc_event' => $request->input('desc_event'),
                    'img_event' => $fileName,
                    'regulasi_event' => $fileNameSnk,
                    'nama_bank' => $request->input('nama_bank'),
                    'nomor_rekening' => $request->input('no_rekening'),
                    'nama_rekening' => $request->input('nama_rekening'),
                    'jasa_layanan' => $request->input('jasa_layanan'),
                    'is_active' => 'Y',
                    'kontak_info' => $request->input('kontak_info'),
                    'addtime' => Carbon::now()->addHours(7)->format('Y-m-d H:i:s'),
                ]);

                return redirect()->back()->with('success', 'Save success!');
            }
            else if($request->input('proses') == "edit"){
                // Proses upload file jika ada file baru
                if ($request->hasFile('img_event')) {
                    $file = $request->file('img_event');
                    $fileName = date('YmdHis') . '_' . $file->getClientOriginalName();
                    $file->move('public/img', $fileName);
                } else {
                    $fileName = $request->input('old_img_event');
                }

                // Update data di database
                DB::table('event')
                    ->where('id_event', $request->input('rowid'))
                    ->update([
                        'img_event' => $fileName,
                        'title_event' => $request->input('title_event'),
                        'desc_event' => $request->input('desc_event'),
                        'is_active' => $request->input('is_active'),
                        'nama_bank' => $request->input('nama_bank'),
                        'nomor_rekening' => $request->input('nomor_rekening'),
                        'nama_rekening' => $request->input('nama_rekening'),
                        'kontak_info' => $request->input('kontak_info'),
                    ]);

                return redirect()->back()->with('success', 'Edit event success!');
            }
            else{
                //delete
                DB::table('event')->where('id_event', $request->input('rowid'))->delete();

                return redirect()->back()->with('success', 'Delete event success!');
            }
        }catch(\Exception $e){
            Log::error('Error occurred : ' . $e->getMessage());
            return redirect()->back()->with('success', 'Save event failed : ' . $e->getMessage());
        }
    }

    public function kategori()
    {
        try{
            if (!session()->has('user_id')) {
                return redirect('/');
            }
            
            $listKategori = DB::table('kategori')
            ->join('event', 'kategori.id_event', '=', 'event.id_event')
            ->select('kategori.*', 'event.*')
            ->get();
            
            $listEvent = DB::table('event')->get();

            return view('masterkategori', [
                'data' => $listKategori,
                'event' => $listEvent
            ]);
        }catch(\Exception $e){
            Log::error('Error occurred report : ' . $e->getMessage());
            return view('masterkategori', ['error' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function postkategori(Request $request)
    {
        try{

            if (!session()->has('user_id')) {
                return redirect('/');
            }
            
            if($request->input('proses') == "save"){
                
                // Simpan data ke database
                DB::table('kategori')->insert([
                    'id_kategori' => 'kategori_'.str_replace(' ', '', $request->input('nama_kategori')),
                    'nama_kategori' => $request->input('nama_kategori'),
                    'harga_kategori' => $request->input('harga_kategori'),
                    'id_event' => $request->input('event'),
                ]);

                return redirect()->back()->with('success', 'Save success!');
            }
            else if($request->input('proses') == "edit"){
                
                // Update data di database
                DB::table('kategori')
                    ->where('id_kategori', $request->input('rowid'))
                    ->update([
                        'nama_kategori' => $request->input('nama_kategori'),
                        'harga_kategori' => $request->input('harga_kategori'),
                    ]);

                return redirect()->back()->with('success', 'Edit kategori success!');
            }
            else{
                //delete
                DB::table('kategori')->where('id_kategori', $request->input('rowid'))->delete();

                return redirect()->back()->with('success', 'Delete kategori success!');
            }
        }catch(\Exception $e){
            Log::error('Error occurred : ' . $e->getMessage());
            return redirect()->back()->with('success', 'error : ' . $e->getMessage());
        }
    }

}