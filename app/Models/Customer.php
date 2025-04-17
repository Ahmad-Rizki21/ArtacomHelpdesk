<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'name',
        'customer_id',
        'ip_address',
        'service',
        'composite_data',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            // Cek apakah customer_id sudah ada sebelum membuat data baru
            if (Customer::where('customer_id', $customer->customer_id)->exists()) {
                throw new \Exception("Data yang Anda masukkan sudah terdaftar di dalam Database.");
            }

            // Mengatur nomor pelanggan secara otomatis
            $customer->no = (Customer::max('no') ?? 0) + 1;

            // Menyimpan kombinasi data
            $customer->composite_data = "{$customer->name} - {$customer->customer_id} - {$customer->ip_address}";
        });

        static::deleting(function ($customer) {
            // Mengurangi nomor pelanggan secara lebih efisien setelah penghapusan
            Customer::where('no', '>', $customer->no)->decrement('no');
        });
    }
}

