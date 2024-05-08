<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $table = 'equipments';


    protected $fillable = [
        'uuid',
        'category',
        'name',
        'description',
        'unit',
        'property',
        'unitary_value',
        'company_uuid'
    ];
}
