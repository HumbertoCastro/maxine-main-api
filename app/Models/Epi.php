<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epi extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'category',
        'name',
        'description',
        'property',
        'unit',
        'unitary_value',
        'company_uuid'
    ];
}
