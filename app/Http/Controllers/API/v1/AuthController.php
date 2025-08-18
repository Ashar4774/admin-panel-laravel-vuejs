<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\API\v1\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function me(Request $request){
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->roles()
                            ->with('permissions')
                            ->get()
                            ->pluck('permissions.*.name')
                            ->flatten()
                            ->unique()
                            ->values()
        ]);
    }

    function login(LoginRequest $request){
        try {
            $user = User::where('email', $request->email)->first();
            if( !$user || ! Hash::check($request->password, $user->password)){
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect']
                ]);
            }

            $token =$user->createToken('user_token')->plainTextToken;
            return response()->json([
                'token' => $token
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ], 401);
        }
    }

    function checkAuthStatus(){
        try {
            $auth = Auth::check() ? true : false;
            return response()->json([
                'status' => $auth
            ], 201);
        } catch(\Exception){
            return response()->json([
                'message' => 'Result not found'
            ], 404);
        }
    }

    function logout(Request $request){
        try{
            $user = $request->user();

            $user->tokens()->delete();

            return response()->json([
                'message' => 'User logged in successfully'
            ], 201);
        } catch(\Exception $e){
            dd($e->getMessage());
        }

    }
}
