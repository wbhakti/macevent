<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function postlogin(Request $request)
    {
        try {

            $passHash = base64_encode(hash_hmac('sha256', $request->input('username') . ':' . $request->input('password'), '#@R4dJaAN91n?#@', true));
            // Cek apakah username dan password cocok
            $user = DB::table('user')
                ->where('username', $request->input('username'))
                ->where('password', $passHash)
                ->where('status', 'active')
                ->first();

            if ($user) {
                // Redirect ke halaman dashboard atau halaman lainnya
                session(['user_id' => $request->input('username'), 'role' => $user->role]);
                return redirect()->route('dashboard');
            } else {
                // Jika username tidak ditemukan atau password salah, kembali ke halaman login dengan pesan error
                return back()->with('error', 'Username atau password salah');
            }
        } catch (\Exception $e) {
            //dd($e);
            Log::error('Error occurred report : ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan : ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        // Menghapus semua data dari sesi
        $request->session()->flush();
        return redirect()->route('login');
    }

}
