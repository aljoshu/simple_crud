<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id_kategori';
    protected $fillable = [
        'id_kategori',
        'nama_kategori',
    ];
    public $timestamps = false;
}
