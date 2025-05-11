<?php

namespace App\Models\AlfaLawson;

use Illuminate\Database\Eloquent\Model;

class TableRemote extends Model
{
    protected $table = 'table_remote';
    protected $primaryKey = 'Site_ID';
    public $incrementing = false; // Karena primary key-nya bukan integer
    protected $keyType = 'string';

    protected $connection = 'alfalawson'; // Gyyyy

    protected $fillable = [
        'Site_ID',
        'Nama_Toko',
        'DC',
        'IP_Address',
        'Vlan',
        'Controller',
        'Customer',
        'Online_Date',
        'Link',
        'Status',
        'Keterangan',
    ];

    protected $casts = [
        'Online_Date' => 'date',
    ];
}