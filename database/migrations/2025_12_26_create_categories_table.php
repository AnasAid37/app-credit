<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. إنشاء جدول Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('couleur')->default('#6366f1'); // لون مميز
            $table->string('icone')->default('fas fa-box'); // أيقونة
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        // 2. إضافة category_id لجدول products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        
        Schema::dropIfExists('categories');
    }
};