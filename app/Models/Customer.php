<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    // Izibiz tarafındaki `id`'yi primary key olarak kullanıyoruz.
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'data',
        'search_text',
    ];
}

