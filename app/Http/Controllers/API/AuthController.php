<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Socialite;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $token = auth('api')->attempt($request->only('email', 'password'));

            if(!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'errors' => [
                        'Email or password is incorrect'
                    ]
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Authorized',
                'data' => [
                    'token' => $token,
                    'expires_in'   => auth('api')->factory()->getTTL() * 60,
                ]
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[Login] - ' . $exception);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors'  => [
                    'Something went wrong. Please try again later.'
                ],
            ], 500);
        }
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'birth_date' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors(),
            ], 422);
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

            return response()->json([
                'success' => true,
                'message' => 'Registered successfully',
                'data' => []
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[Register] - ' . $exception);
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors'  => [
                    'Something went wrong. Please try again later.'
                ],
            ], 500);
        }
    }

    public function logout(Request $request) {
        DB::beginTransaction();
        try {
            auth('api')->user()->update([
                'google_token' => null,
                'google_refresh_token' => null,
                'facebook_token' => null,
                'facebook_refresh_token' => null,
            ]);
            auth('api')->invalidate(true);
            auth('api')->unsetToken();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
                'data' => []
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[Logout] - ' . $exception);
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors'  => [
                    'Something went wrong. Please try again later.'
                ],
            ], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $token = auth('api')->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed',
                'data' => [
                    'token' => $token,
                ]
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[Refresh Token] - ' . $exception);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors'  => [
                    'Something went wrong. Please try again later.'
                ],
            ], 500);
        }
    }


    // Google and Facebook

    public function googleCallback(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate([
                'email' => $user->email,
            ], [
                'name' => $user->name,
                'email' => $user->email,
                'google_token' => $user->token,
                'google_refresh_token' => $user->refreshToken,
            ]);

            DB::commit();

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Authorized',
                'data' => [
                    'token' => $token,
                    'expires_in'   => auth('api')->factory()->getTTL() * 60,
                ]
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[Callback - Google] - ' . $exception);
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors'  => [
                    'Something went wrong. Please try again later.'
                ],
            ], 500);
        }
    }

    public function facebookCallback(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Socialite::driver('facebook')->stateless()->user();

            $user = User::updateOrCreate([
                'email' => $user->email,
            ], [
                'name' => $user->name,
                'email' => $user->email,
                'facebook_token' => $user->token,
                'facebook_refresh_token' => $user->refreshToken,
            ]);

            DB::commit();

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Authorized',
                'data' => [
                    'token' => $token,
                    'expires_in'   => auth('api')->factory()->getTTL() * 60,
                ]
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[Callback - Facebook] - ' . $exception);
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors'  => [
                    'Something went wrong. Please try again later.'
                ],
            ], 500);
        }
    }
}
