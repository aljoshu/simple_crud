<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_produk',
        'nama_produk',
        'harga',
        'kategori_id',
        'status_id'
    ];
    public $timestamps = false;

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id', 'id_kategori');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id_status');
    }
}
