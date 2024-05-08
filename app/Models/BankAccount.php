<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'account_number',
        'account_type',
        'account_holder',
        'cpf_cnpj',
        'bank',
        'agency',
        'balance',
        'company_uuid',
        'is_active'
    ];
}
