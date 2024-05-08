<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Releases extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'account_uuid',
        'amount',
        'type',
        'description',
        'company_uuid',
        'user_uuid',
        'payment_method_uuid',
    ];
}
