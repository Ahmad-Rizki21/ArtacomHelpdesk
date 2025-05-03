<?php

namespace App\Models\AlfaLawson;

use Illuminate\Database\Eloquent\Model;

class CustomerAlfa extends Model
{
    protected $connection = 'alfalawson'; // Penting! Ini menentukan koneksi database
    
    protected $table = 'customers';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
}