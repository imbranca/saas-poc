<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    //
  public function login(Request $request){
    //TODO: add validations
    Auth::attempt($request->only('email', 'password'));
    $user = Auth::user();
    $user->tokens()->delete();
    $token = $user->createToken('token')->plainTextToken;
    $cookie = cookie('jwt', $token, 60*24);
    return response([
      'data'=> $user,
      "message"=>'login'
    ], Response::HTTP_OK)->cookie($cookie);
  }

  public function logout(Request $request){
   $request->user()->currentAccessToken()->delete();
    return response()->json
    ([
      'data'=> null,
      'message' => 'Logged out',
    ],Response::HTTP_OK)->cookie('jwt', '', -1);
  }

  public function profile(){
    $user = Auth::user();
    return response([
      'data'=>$user,
      'message'=>'user information'
    ], Response::HTTP_OK);
  }
}
