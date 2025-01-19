<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function funLogin(Request $request) {
        // validar los datos 
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // autenticar
        if(!Auth::attempt($credenciales)){
            return response()->json(["mensaje" => "Credenciales Incorrectas"],401);
        }

        // generamos un token

        $token = $request->user()->createToken("Token auth")->plainTextToken;
        return response([
            "access_token" => $token,
            "ususario" =>  $request->user()
        ], 201);
    }
    public function funRegister(Request $request) {
        // validar los datos 
        $request->validate([
           "name" => "required|max:100|min:2|string",
           "email" => "required|email|unique:users",
           "password" => "required|same:c_password"
        ]);
        // registrar en la BD
        $usuario = new User();
        $usuario->name= $request->name;
        $usuario->email= $request->email;
        $usuario->password= Hash::make($request->password);
        $usuario->save();
        // respoder
        return response()->json(["mensaje"=> "Usuario Registrado"]);
    }
    public function funProfile(Request $request) {
        return response()->json($request->user(),200);
    }
    public function funLogout(Request $request) {}
}
