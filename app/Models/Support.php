<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory, UuidTrait;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'description',
        'status'
    ];

    public $statusOptions = [
        'P' => 'Pendente, Aguardando Professor',
        'A' => 'Aaguardando Aluno',
        'C' => 'Concluído'
    ];
}
