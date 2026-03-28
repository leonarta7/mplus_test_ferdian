<?php


namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Socialite;

class MainController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function login()
    {
        return view('login');
    }

    public function submitLogin(Request $request) {
        try {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->intended('/');
            }

            return redirect()->back()->withInput()->withErrors([
                'Email or password is incorrect'
            ]);
        } catch (Exception $exception) {
            Log::error('[Login] - '.$exception);

            return redirect()->back()->withInput()->withErrors([
                'Internal Server Error'
            ]);
        }
    }

    public function thirdPartyLogin(Request $request)
    {
        return Socialite::driver($request->input('type'))->stateless()->redirect();
    }

    public function register()
    {
        return view('register');
    }

    public function submitRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'birth_date' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        DB::beginTransaction();
        try {
            User::create([
                'name' => $request->input('name'),
                'gender' => $request->input('gender'),
                'birth_date' => $request->input('birth_date'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registration successful!');
        } catch (Exception $exception) {
            Log::error('[Register] - '.$exception);
            DB::rollBack();

            return redirect()->back()->withInput()->withErrors([
                'Internal Server Error'
            ]);
        }
    }

    public function logout() {
        Auth::logout();

        return redirect('/');
    }
}
