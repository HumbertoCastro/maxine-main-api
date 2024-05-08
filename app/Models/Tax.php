<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $table = 'tax';

    protected $fillable = [
        'uuid',
        'type',
        'acronym',
        'name',
        'description',
        'aliquot',
        'company_uuid'
    ];
}
