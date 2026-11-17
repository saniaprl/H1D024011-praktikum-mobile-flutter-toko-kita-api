<?php

namespace App\Models;

use CodeIgniter\Model;

class MProduk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_produk',
        'nama_produk',
        'harga'
    ];

    protected $useTimestamps = false;
}
