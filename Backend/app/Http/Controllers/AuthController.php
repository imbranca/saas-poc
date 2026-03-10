<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    //
  public function login(Request $request){
    if (!Auth::attempt($request->only('email', 'password'))) {
      return response([
        'message' => 'invalid credenctials',
        'success' => false,
      ], Response::HTTP_UNAUTHORIZED);
    }

    Auth::attempt($request->only('email', 'password'));
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
      'data'=> $user,
      "message"=>'login'
    ], Response::HTTP_OK)->cookie($cookie);
  }

  public function logout(Request $request){
    if(!Auth::user()){
       return response([
        'message' => 'invalid credenctials',
        'success' => false,
      ], Response::HTTP_UNAUTHORIZED);
    }
   $request->user()->currentAccessToken()->delete();
    return response()->json
    ([
      'data'=> null,
      'message' => 'Logged out',
    ],Response::HTTP_OK)->cookie('jwt', '', -1);
  }

  public function profile(){
    //
    $user = Auth::user();
    if(!$user){
       return response([
        'message' => 'invalid credenctials',
        'success' => false,
      ], Response::HTTP_UNAUTHORIZED);
    }

    return response([
      'data'=>$user,
      'message'=>'user information'
    ], Response::HTTP_OK);
  }
}
