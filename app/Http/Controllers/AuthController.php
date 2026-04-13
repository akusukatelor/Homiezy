<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin() { 
        return view('auth.login'); 
    }

    public function showRegister() { 
        return view('auth.register'); 
    }
    public function redirectToGoogle() {
    return Socialite::driver('google')
        ->with(['prompt' => 'select_account']) // Menampilkan layar pilih akun
        ->redirect();
}

    // Callback dari Google
    public function handleGoogleCallback() {
        $googleUser = Socialite::driver('google')->user();
        
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            // Jika user sudah ada tapi belum punya google_id, update saja
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }
        }else if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(str()->random(16)),
            ]);
        }
        

        Auth::login($user);

        // Jika WhatsApp belum diisi, arahkan untuk melengkapi data
        if (!$user->whatsapp) {
            return redirect()->route('complete.profile');
        }

        return redirect()->route('dashboard');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
}

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
}

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'whatsapp' => 'required|string|max:15', // Untuk koordinasi katering/laundry
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Akun Homiezy berhasil dibuat!');
    }

    public function updateProfile(Request $request) {
        $request->validate([
            'whatsapp' => 'required|string|max:15',
        ]);

        $user = Auth::user();
        $user->update([
            'whatsapp' => $request->whatsapp
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil dilengkapi!');
    }
}

