<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SugestaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('sugestaos')->insert([

            [
                'nome' => 'Peito de Frango Grelhado',
                'descricao' => 'Peito de frango grelhado com especiarias e azeite.',
                'calorias' => 165,
                'proteinas' => 31.0,
                'carboidratos' => 0.0,
                'gorduras' => 3.6,
                'categoria' => '+calorias',
                'restricoes_para' => 'Nenhuma',
            ],
            [
                'nome' => 'Smoothie de Banana e Aveia',
                'descricao' => 'Smoothie nutritivo feito com banana, aveia, e leite vegetal.',
                'calorias' => 250,
                'proteinas' => 6.0,
                'carboidratos' => 45.0,
                'gorduras' => 5.0,
                'categoria' => '+calorias',
                'restricoes_para' => 'Intolerância à lactose',
            ],
            [
                'nome' => 'Omelete de Clara de Ovos',
                'descricao' => 'Omelete feita com claras de ovos, espinafre e tomate.',
                'calorias' => 150,
                'proteinas' => 24.0,
                'carboidratos' => 4.0,
                'gorduras' => 3.0,
                'categoria' => '+calorias',
                'restricoes_para' =>  'Nenhuma',
            ],
            [
                'nome' => 'Quinoa com Frango Desfiado',
                'descricao' => 'Quinoa cozida com frango desfiado e vegetais frescos.',
                'calorias' => 300,
                'proteinas' => 28.0,
                'carboidratos' => 40.0,
                'gorduras' => 8.0,
                'categoria' => '+calorias',
                'restricoes_para' => 'Nenhuma',
            ],

            // Sugestões para +/-calorias
            [
                'nome' => 'Salada de Quinoa com Abacate',
                'descricao' => 'Quinoa cozida com legumes frescos e abacate.',
                'calorias' => 220,
                'proteinas' => 8.0,
                'carboidratos' => 39.0,
                'gorduras' => 6.0,
                'categoria' => '+/-calorias',
                'restricoes_para' => 'Nenhuma',
            ],
            [
                'nome' => 'Iogurte Natural com Granola',
                'descricao' => 'Iogurte natural com granola e frutas frescas.',
                'calorias' => 180,
                'proteinas' => 8.0,
                'carboidratos' => 25.0,
                'gorduras' => 5.0,
                'categoria' => '+/-calorias',
                'restricoes_para' => 'Intolerância à lactose',
            ],
            [
                'nome' => 'Filé de Salmão Grelhado',
                'descricao' => 'Filé de salmão grelhado com ervas e azeite de oliva.',
                'calorias' => 250,
                'proteinas' => 30.0,
                'carboidratos' => 0.0,
                'gorduras' => 12.0,
                'categoria' => '+/-calorias',
                'restricoes_para' => 'Mariscos',
            ],
            [
                'nome' => 'Arroz Integral com Legumes',
                'descricao' => 'Arroz integral cozido com legumes variados.',
                'calorias' => 200,
                'proteinas' => 6.0,
                'carboidratos' => 40.0,
                'gorduras' => 3.0,
                'categoria' => '+/-calorias',
                'restricoes_para' => 'Nenhuma' ,
            ],

            // Sugestões para -calorias
            [
                'nome' => 'Batata Doce Assada',
                'descricao' => 'Batata doce assada no forno com azeite e alecrim.',
                'calorias' => 180,
                'proteinas' => 4.0,
                'carboidratos' => 41.0,
                'gorduras' => 0.5,
                'categoria' => '-calorias',
                'restricoes_para' =>  'Nenhuma',
            ],
            [
                'nome' => 'Peixe Branco Grelhado com Legumes',
                'descricao' => 'Filé de peixe branco grelhado com legumes cozidos.',
                'calorias' => 190,
                'proteinas' => 25.0,
                'carboidratos' => 5.0,
                'gorduras' => 7.0,
                'categoria' => '-calorias',
                'restricoes_para' => 'Mariscos',
            ],
            [
                'nome' => 'Sopa de Legumes',
                'descricao' => 'Sopa nutritiva de legumes com baixo teor calórico.',
                'calorias' => 120,
                'proteinas' => 3.0,
                'carboidratos' => 20.0,
                'gorduras' => 2.0,
                'categoria' => '-calorias',
                'restricoes_para' =>  'Nenhuma' ,
            ],
            [
                'nome' => 'Frango Desfiado com Abobrinha',
                'descricao' => 'Frango desfiado refogado com abobrinha e temperos naturais.',
                'calorias' => 140,
                'proteinas' => 25.0,
                'carboidratos' => 4.0,
                'gorduras' => 3.0,
                'categoria' => '-calorias',
                'restricoes_para' => 'Nenhuma',
            ],
        ]);
    }
}
