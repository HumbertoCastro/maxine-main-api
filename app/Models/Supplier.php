<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'phone',
        'email',
        'cnpj',
        'address',
        'number',
        'district',
        'city',
        'uf',
        'cep',
        'observation',
        'company_uuid',
        'is_active',
    ];
}
