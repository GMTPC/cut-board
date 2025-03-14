<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
   
     public function login(Request $request)
     {
         $credentials = $request->validate([
             'name' => 'required|string', // ใช้ name แทน username
             'password' => 'required|string',
         ]);
     
         if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
             $request->session()->regenerate();
             return redirect()->intended('/dashboard');
         }
     
         return back()->withErrors(['name' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
     }
     
    
    

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
