<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoot extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'company_name',
        'company_logo',
        'user_name',
        'user_email',
        'user_phone',
        'password',
        'cpf_cnpj',
        'studio_uuid',
        'is_active'
    ];
}
