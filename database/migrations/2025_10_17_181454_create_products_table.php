<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2);
            $table->string('taille');
            $table->string('marque')->nullable();
            $table->integer('quantite')->default(0);
            $table->integer('seuil_alerte')->default(5);
            $table->timestamps();
            
            $table->unique(['price', 'taille']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};