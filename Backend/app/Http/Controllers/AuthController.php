<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    //
  public function login(Request $request){
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    if (!Auth::attempt($request->only('email', 'password'))) {
      return response([
        'message' => 'invalid credenctials',
        'success' => false,
      ], Response::HTTP_UNAUTHORIZED);
    }

    $user = Auth::user();
    $user->tokens()->delete();
    $token = $user->createToken('token')->plainTextToken;
    $cookie = cookie('jwt', $token,
    60*24,
    '/',
    'localhost',
    false,
    true);
    return response([
      'data'=> new UserResource($user),
      "message"=>'login'
    ], Response::HTTP_OK)->cookie($cookie);
  }

  public function logout(Request $request){

   $request->user()->currentAccessToken()->delete();
    return response()->json
    ([
      'data'=> null,
      'message' => 'Logged out',
    ],Response::HTTP_OK)->withoutCookie('jwt');
  }

  public function profile(){
    //
    $user = Auth::user();

    return response([
      'data'=> new UserResource($user),
      'message'=>'user information'
    ], Response::HTTP_OK);
  }
}
