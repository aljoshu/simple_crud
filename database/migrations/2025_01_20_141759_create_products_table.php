<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->string('nama_produk');
            $table->decimal('harga', 9, 2);
            $table->unsignedInteger('kategori_id');
            $table->unsignedInteger('status_id');
            // $table->timestamps();

            $table->foreign('kategori_id')->references('id_kategori')->on('categories')->onDelete('cascade');
            $table->foreign('status_id')->references('id_status')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
