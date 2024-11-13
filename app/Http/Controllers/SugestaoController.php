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


    public function criarPlanoAlimentar($id): JsonResponse
    {
        try {
            // Busca o usuário e valida
            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado.'], 404);
            }

            // Obtem restrições e necessidade calórica
            $restricoes = explode(',', $user->restricoes); // Supondo que as restrições estejam armazenadas em uma string separada por vírgulas
            $caloriasDiarias = $user->necessidade_calorica;

            // Filtra sugestões sem as restrições do usuário
            $sugestoes = Sugestao::whereNotIn('restricoes_para', $restricoes)->get()->toArray();
            $resultado = [];
            $caloriasPorRefeicao = $caloriasDiarias / 3;
                
            // Função auxiliar para a busca DFS
            function dfs($sugestoes, $caloriasPorRefeicao, $combinacaoAtual, &$resultado, $indiceAtual, $refeicao)
            {
                // Calcula as calorias da combinação atual
                $caloriasAtuais = array_reduce($combinacaoAtual, function ($total, $item) {
                    return $total + $item['calorias'];
                }, 0);

                // Adiciona a combinação ao resultado se atingir o objetivo calórico da refeição
                if ($caloriasAtuais >= $caloriasPorRefeicao) {
                    $resultado[$refeicao][] = $combinacaoAtual;
                    return true;
                }

                // Busca recursiva por combinações de sugestões
                for ($i = $indiceAtual; $i < count($sugestoes); $i++) {
                    $sugestao = $sugestoes[$i];
                    $combinacaoAtual[] = $sugestao;

                    // Chama a função recursivamente
                    if (dfs($sugestoes, $caloriasPorRefeicao, $combinacaoAtual, $resultado, $i + 1, $refeicao)) {
                        return true;
                    }

                    // Remove a última sugestão ao voltar da chamada recursiva
                    array_pop($combinacaoAtual);
                }

                return false;
            }

            // Executa o DFS para compor as três refeições
            reiniciar:;
            array_splice($resultado,0);
            foreach (['cafe_da_manha', 'almoco', 'jantar'] as $refeicao) {
                shuffle($sugestoes);  // Embaralha as sugestões
                dfs($sugestoes, $caloriasPorRefeicao, [], $resultado, 0, $refeicao);
            }

            // Verifica se alguma combinação de refeições atende ao total de calorias diárias
            
            foreach ($resultado['cafe_da_manha'] as $cafe) {
                foreach ($resultado['almoco'] as $almoco) {
                    foreach ($resultado['jantar'] as $jantar) {

                        $caloriasCafe = array_reduce(array_merge($cafe), function ($total, $item) {
                            return $total + $item['calorias'];
                        }, 0);

                        $caloriasAlmoco = array_reduce(array_merge($almoco), function ($total, $item) {
                            return $total + $item['calorias'];
                        }, 0);

                        $caloriasJantar = array_reduce(array_merge($jantar), function ($total, $item) {
                            return $total + $item['calorias'];
                        }, 0);

                        // Calcular as calorias totais
                        $caloriasTotal = array_reduce(array_merge($cafe, $almoco, $jantar), function ($total, $item) {
                            return $total + $item['calorias'];
                        }, 0);

                        // Calorias esperadas para cada refeição
                        $caloriasPorRefeicao = $caloriasDiarias / 3;
                        $direfecaCalorias = 0;
                        // Ajuste para o café da manhã se as calorias forem maiores que o esperado
                        if ($caloriasCafe > $caloriasPorRefeicao & sizeof($cafe) > 1) {
                            $direfecaCalorias = $caloriasCafe - $caloriasPorRefeicao;

                            //remover o ultimo elemento
                            array_pop($cafe);
                        }

                        if ($caloriasJantar < $caloriasPorRefeicao + $direfecaCalorias & sizeof($jantar) > 1) {
                            $diferenca = $caloriasPorRefeicao - $caloriasJantar;
                            array_pop($jantar);
                        }

                        // Se o almoço tiver menos calorias que o esperado, ajustar com o valor da refeição anterior
                        if ($caloriasAlmoco < $caloriasPorRefeicao + $direfecaCalorias & sizeof($almoco) > 1) {
                            $diferenca = $caloriasPorRefeicao - $caloriasAlmoco;
                            array_pop($almoco);
                        }

                        $caloriasTotal = array_reduce(array_merge($cafe, $almoco, $jantar), function ($total, $item) {
                            return $total + $item['calorias'];
                        }, 0);
                        // Verificação de calorias totais
                        if ($caloriasTotal <= $caloriasDiarias) {

                            return response()->json([
                                'cafe_da_manha' => $cafe,
                                'almoco' => $almoco,
                                'jantar' => $jantar
                            ], 200, [], JSON_PRETTY_PRINT);
                        } else {
                            goto reiniciar;
                        }
                        return response()->json([
                            'cafe_da_manha' => $caloriasCafe,
                            'almoco' => $caloriasAlmoco,
                            'jantar' => $caloriasJantar,
                            'Total' => $caloriasTotal
                        ], 200, [], JSON_PRETTY_PRINT);
                    }
                }
            }


            // Retorna uma mensagem em JSON se nenhuma combinação atender aos requisitos
            return response()->json(['message' => 'Não foi possível encontrar um plano alimentar que atenda às restrições e calorias diárias desejadas.'], 200);
        } catch (\Exception $e) {
            // Captura qualquer erro e retorna uma mensagem de erro com o código de status 500
            return response()->json([
                'error' => 'Ocorreu um erro ao tentar criar o plano alimentar.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
