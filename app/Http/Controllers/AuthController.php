<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {


        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'sexo' => 'required|in:1,3',
            'senha' => 'required|string|min:8',
            'idade' => 'required|integer',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'objectivos'=> 'required|string',
            'restricoes' => 'required|string|max:100',

        ]);

        $tmb =0;

        if(intval($validated['sexo']) === 1){
            $tmb = (10 * intval($validated['peso'])) + (6.25 * intval($validated['altura'])) - (5 * intval($validated['idade'])) + 5;

        }elseif (intavl($validated['sexo']) === 2){
            $tmb = (10 * intval($validated['peso'])) + (6.25 * intval($validated['altura'])) - (5 * intval($validated['idade'])) - 161;

        }

        if($validated['objectivos'] === 'Perder peso'){
            $nsc = $tmb - 500;
        }elseif ($validated['objectivos'] === 'Ganhar massa muscular'){
            $nsc = $tmb + 1000;
        }else{
            $nsc = $tmb;
        }

        $user = User::create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['senha']),
            'idade' => $validated['idade'],
            'sexo' => $validated['sexo']=== 2 ? "Mulher": "Homem",
            'peso' => $validated['peso'],
            'altura' => $validated['altura'],
            'objetivo' => $validated['objectivos'],
            'restricoes' => $validated['restricoes'],
            'necessidade_calorica' => $nsc,
        ]);

        // Gerar token de autenticação
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->id,
        ]);
    }


    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'senha' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['senha'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->id,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }



}
