<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugestao extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'calorias',
        'proteinas',
        'carboidratos',
        'gorduras',
        'categoria',
        'restricoes_para'
    ];
}
