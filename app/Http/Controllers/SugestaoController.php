<?php

namespace App\Http\Controllers;

use App\Models\Sugestao;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SugestaoController extends Controller
{
    public function sugerirRefeicao($id): JsonResponse
    {
        // Busca o perfil do usuário
        $userProfile = User::findOrFail($id);

        // Regras simples de dieta
        $sugestao = $this->aplicarRegras($userProfile);
//        $restricao =
        // Retorna a sugestão como resposta JSON

        $food = Sugestao::where('categoria' , $sugestao)->get();

        return response()->json([
            'sugestao' => $food
        ]);
    }
    public function todasRefeicao (): JsonResponse{

        $food = Sugestao::all();
        return response()->json([
            'sugestao' => $food
        ]);
    }

    // Função para aplicar as regras
    private function aplicarRegras($userProfile)
    {
        $sugestao = null;

        // Regra 1: Perder peso
        if ($userProfile->objetivo === 'Perder peso') {
            $sugestao = '-calorias';
        }

        // Regra 2: Ganhar massa muscular
        if ($userProfile->objetivo === 'Ganhar massa muscular') {
            $sugestao = '+calorias';
        }

        // Regra 3: Restrições alimentares
        if ($userProfile->objetivo === 'Manter o peso') {
            $sugestao = '+/-calorias';
        }

        return $sugestao;
    }
}
