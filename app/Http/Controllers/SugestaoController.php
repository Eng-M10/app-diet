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

        $food = Sugestao::where('categoria', $sugestao)->get();

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


    public function criarPlanoAlimentar($id, $numeroRefeicoes): JsonResponse
    {
        try {
            // Busca o usuário e valida
            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado.'], 404);
            }
            if ($numeroRefeicoes < 2 && $numeroRefeicoes > 5) {
                return response()->json(['error' => 'Número Inválido de Refeições escolha um intervalo de 2-5'], 401);
            }

            // Obtem restrições e necessidade calórica
            $restricoes = explode(',', $user->restricoes);
            $caloriasDiarias = $user->necessidade_calorica;
            $sugestoes = Sugestao::whereNotIn('restricoes_para', $restricoes)->get()->toArray();
            $resultado = [];
            $caloriasPorRefeicao = $caloriasDiarias / $numeroRefeicoes;
            $objetivo = $user->objetivo;
            $nomesRedeicoes=[];
            switch ($numeroRefeicoes) {
                case 2:
                    $nomeRefeicoes = ['Almoço', 'Jantar'];
                    break;
                case 3:
                    $nomeRefeicoes = ['Café Da Manhã', 'Almoço', 'Jantar'];
                    break;
                case 4:
                    $nomeRefeicoes = ['Café Da Manhã', 'Almoço', 'Lanche Da Tarde', 'Jantar'];
                    break;
                case 5:
                    $nomeRefeicoes = ['Café Da Manhã', 'Lanhe Da Manhã', 'Almoço', 'Lanhe Da Tarde', 'Jantar'];
            }


            // Função auxiliar para a busca DFS
            function dfs($sugestoes, $caloriasPorRefeicao, $combinacaoAtual, &$resultado, $indiceAtual, $refeicao)
            {
                $caloriasAtuais = array_reduce($combinacaoAtual, function ($total, $item) {
                    return $total + $item['calorias'];
                }, 0);

                if ($caloriasAtuais >= $caloriasPorRefeicao) {
                    $resultado[$refeicao][] = $combinacaoAtual;
                    return true;
                }

                for ($i = $indiceAtual; $i < count($sugestoes); $i++) {
                    $sugestao = $sugestoes[$i];
                    $combinacaoAtual[] = $sugestao;
                    if (dfs($sugestoes, $caloriasPorRefeicao, $combinacaoAtual, $resultado, $i + 1, $refeicao)) {
                        return true;
                    }
                    array_pop($combinacaoAtual);
                }

                return false;
            }

            // Tenta compor refeições com calorias adequadas para o número de refeições desejado
            reiniciar:;
            array_splice($resultado,0);
            do {
                $resultado = [];
                for ($i = 0; $i < $numeroRefeicoes; $i++) {
                    $refeicao = $nomeRefeicoes[$i];
                    $nomesRedeicoes[$i]['nome']=$nomeRefeicoes[$i];
                    shuffle($sugestoes);
                    dfs($sugestoes, $caloriasPorRefeicao, [], $resultado, 0, $refeicao);
                }

                // Calcula o total de calorias para verificar se atende aos requisitos
                $caloriasTotal = 0;
                foreach (array_slice($nomeRefeicoes, 0, $numeroRefeicoes) as $refeicao) {
                    foreach ($resultado[$refeicao] as $comb) {
                        $caloriasTotal += array_reduce($comb, function ($total, $item) {
                            return $total + $item['calorias'];
                        }, 0);
                    }
                }

                // Verifica se o total de calorias está dentro do limite para o objetivo do usuário
                if (($objetivo == 'Ganhar massa muscular' && $caloriasTotal >= $caloriasDiarias && $caloriasTotal <= $caloriasDiarias + 50) ||
                    (($objetivo == 'Perder peso' || $objetivo == 'Manter o peso') && $caloriasTotal <= $caloriasDiarias && $caloriasDiarias - $caloriasTotal < 50)
                ) {
                    $planoAlimentar = [];
                    foreach (array_slice($nomeRefeicoes, 0, $numeroRefeicoes) as $refeicao) {
                        $planoAlimentar[$refeicao] = $resultado[$refeicao];
                    }
                    
                    return response()->json([
                        'planoAlimentar' => $planoAlimentar,
                        'refeicao' => $nomesRedeicoes,
                        'Calorias' => $caloriasTotal
                    ], 200, [], JSON_PRETTY_PRINT);
                }else{
                    goto reiniciar;
                }
            } while (true);

            return response()->json(['message' => 'Não foi possível encontrar um plano alimentar que atenda às restrições e calorias diárias desejadas.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocorreu um erro ao tentar criar o plano alimentar.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
