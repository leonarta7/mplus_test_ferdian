<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Socialite;

class UserController extends Controller
{
    public function info(Request $request)
    {
        try {
            $user = $request->user('api');

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'errors'  => [
                        "User data doesn't exist"
                    ],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Get user info successful',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'gender' => $user->gender == 'M' ? 'Male' : 'Female',
                    'birth_date' => Carbon::parse($user->birth_date)->format('Y-m-d'),
                ]
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[User Info] - ' . $exception);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'errors'  => [
                    'Something went wrong. Please try again later.'
                ],
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'birth_date' => 'required|date',
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
            $user = $request->user('api');

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'errors'  => [
                        "User data doesn't exist"
                    ],
                ], 404);
            }

            $user->update([
               'name' => $request->input('name'),
               'gender' => $request->input('gender'),
               'birth_date' => $request->input('birth_date'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User info updated successfully',
                'data' => []
            ]);
        } catch (Exception $exception) {
            Log::channel('api_exception')->error('[User Update] - ' . $exception);
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
