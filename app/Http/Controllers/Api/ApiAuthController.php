<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            // Set token expiration time (e.g., 24 hours from now)
            $token = $user->createToken('auth_token', ['*'], now()->addHours(3))->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'expires_at' => now()->addHours(24)->toDateTimeString(), // Add expiration time in response
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'outlet_id' => Auth::user()->outlet_id
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during logout'
            ], 500);
        }
    }

    public function verifyToken(Request $request)
    {
        try {
            // Since we're using auth middleware, if we reach here,
            // the token is already valid
            return response()->json([
                'status' => true,
                'message' => 'Token is valid',
                 // Get authenticated user details
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token verification failed',
                
            ], 500);
        }
    }
}
