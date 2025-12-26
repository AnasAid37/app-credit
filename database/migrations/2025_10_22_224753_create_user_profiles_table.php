<?php
// database/migrations/2024_xx_xx_create_user_profiles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('adresse')->nullable();
            $table->string('departement')->default('Gestion de Stock');
            $table->date('date_embauche')->nullable();
            $table->string('langue')->default('Français');
            $table->string('fuseau_horaire')->default('Africa/Casablanca');
            $table->boolean('notif_stock_faible')->default(true);
            $table->boolean('notif_commandes')->default(true);
            $table->boolean('notif_rapports')->default(true);
            $table->boolean('notif_promotions')->default(false);
            $table->boolean('two_factor_enabled')->default(false);
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};