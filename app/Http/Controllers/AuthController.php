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
            'genero' => 'required|in:1,2',
            'senha' => 'required|string|min:8',
            'idade' => 'required|integer',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'objectivos'=> 'required|string',
            'restricoes' => 'required|string|max:100',

        ]);

        $tmb =0;

        if(intval($validated['genero']) === 1){
            $tmb = (10 * intval($validated['peso'])) + (6.25 * intval($validated['altura'])) - (5 * intval($validated['idade'])) + 5;

        }elseif (intval($validated['genero']) === 2){
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
            'sexo' => $validated['genero']=== 2 ? "Mulher": "Homem",
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

    public function update(Request $request, $id): JsonResponse
{
    try{
    // Encontrar o Utilizador pelo ID
    $user = User::findOrFail($id);

    // Validar os dados de entrada
    $validated = $request->validate([
        'nome' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        'genero' => 'sometimes|required|in:1,2',
        'senha' => 'sometimes|required|string|min:8',
        'idade' => 'sometimes|required|integer',
        'peso' => 'sometimes|required|numeric',
        'altura' => 'sometimes|required|numeric',
        'objectivos'=> 'sometimes|required|string',
        'restricoes' => 'sometimes|required|string|max:100',
    ]);

    // Calcular a Taxa Metabólica Basal (TMB)
    $tmb = 0;
    if (intval($validated['genero']) === 1) {
        $tmb = (10 * intval($validated['peso'])) + (6.25 * intval($validated['altura'])) - (5 * intval($validated['idade'])) + 5;
    } elseif (intval($validated['genero']) === 2) {
        $tmb = (10 * intval($validated['peso'])) + (6.25 * intval($validated['altura'])) - (5 * intval($validated['idade'])) - 161;
    }

    // Calcular a necessidade calórica com base nos objetivos
    $nsc = $tmb;
    if ($validated['objectivos'] === 'Perder peso') {
        $nsc = $tmb - 500;
    } elseif ($validated['objectivos'] === 'Ganhar massa muscular') {
        $nsc = $tmb + 1000;
    }

    // Atualizar o Utilizador com os dados validados
    $user->update([
        'name' => $validated['nome'] ?? $user->name,
        'email' => $validated['email'] ?? $user->email,
        'password' => isset($validated['senha']) ? Hash::make($validated['senha']) : $user->password,
        'idade' => $validated['idade'] ?? $user->idade,
        'sexo' => isset($validated['genero']) && $validated['genero'] === 2 ? "Mulher" : "Homem",
        'peso' => $validated['peso'] ?? $user->peso,
        'altura' => $validated['altura'] ?? $user->altura,
        'objetivo' => $validated['objectivos'] ?? $user->objetivo,
        'restricoes' => $validated['restricoes'] ?? $user->restricoes,
        'necessidade_calorica' => $nsc ?? $user->necessidade_calorica,
    ]);

    return response()->json([
        'message' => 'Utilizador atualizado com sucesso!'
    ]);
}catch(\Exception $e) {
    return response()->json([
        'message' => 'Erro ao atualizar Utilizador: ' . $e->getMessage()
    ], 500); // 500 Internal Server Error
}
}






}
