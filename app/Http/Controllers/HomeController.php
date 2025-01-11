<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $listSlider = DB::table('slider')->get();

            $listEvent = DB::table('event')->paginate(3);

            return view('home', [
                'data' => $listSlider,
                'event' => $listEvent
            ]);
        } catch (\Exception $e) {
            //dd($e);
            Log::error('Error occurred report : ' . $e->getMessage());
            return view('home', ['error' => 'Terjadi kesalahan : ' . $e->getMessage()]);
        }
    }

    public function dashboard()
    {
        if (!session()->has('user_id')) {
            return redirect('/');
        } else {
            return view('dashboard');
        }
    }
    
}
