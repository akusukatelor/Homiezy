<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        return view('auth.register');
    }

    /**
     * Helper untuk menentukan arah redirect berdasarkan role
     */
    private function getRedirectPath($user)
    {
        if ($user->role === 'mitra') {
            return route('mitra.dashboard');
        }
        return route('dashboard'); // Dashboard customer/mahasiswa
    }

   public function redirectToGoogle(Request $request) {
    // Kuncinya di sini: tangkap parameter 'role' dari URL (jika ada)
    if ($request->has('role')) {
        session(['register_as_role' => $request->query('role')]);
    }

    return Socialite::driver('google')
        ->with(['prompt' => 'select_account'])
        ->redirect();
}

    public function handleGoogleCallback() {
    $guzzleClient = new Client([
        'verify' => false,
    ]);
    $googleUser = Socialite::driver('google')->user();
    $user = User::where('email', $googleUser->email)->first();

    if (!$user) {
        // Ambil 'niat' role dari session (yang diset saat redirectToGoogle)
        // Jika tidak ada, barulah default ke 'customer'
        $role = session('register_as_role', 'customer');

        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'password' => Hash::make(str()->random(16)),
            'role' => $role, // <--- Sekarang dinamis!
        ]);

        session()->forget('register_as_role');
    }
        Auth::login($user);

        // Jika WhatsApp belum diisi, wajib lengkapi data profil dulu
        if (!$user->whatsapp) {
            return redirect()->route('complete.profile');
        }

        // Redirect sesuai role
       return redirect()->intended($this->getRedirectPath($user));
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Menggunakan intended() agar redirect lebih pintar
            return redirect()->intended($this->getRedirectPath($user));
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
            'whatsapp' => 'required|string|max:15',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default pendaftaran manual adalah customer
        ]);

        return redirect()->route('login')->with('success', 'Akun Homiezy berhasil dibuat! Silakan login.');
    }

    public function updateProfile(Request $request) {
        $request->validate([
            'whatsapp' => 'required|string|max:15',
        ]);

        $user = Auth::user();
        $user->update([
            'whatsapp' => $request->whatsapp
        ]);

        // Setelah lengkapi profil, kirim ke dashboard sesuai role
        return redirect()->intended($this->getRedirectPath($user))->with('success', 'Profil berhasil dilengkapi!');
    }
}
