<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sorties', function (Blueprint $table) {
            // تغيير اسم الحقل من prix_total إلى total_price إذا كان موجوداً
            if (Schema::hasColumn('sorties', 'prix_total')) {
                $table->renameColumn('prix_total', 'total_price');
            }
            
            // إضافة الحقول الجديدة
            if (!Schema::hasColumn('sorties', 'payment_mode')) {
                $table->enum('payment_mode', ['cash', 'credit'])
                      ->default('cash')
                      ->after('motif_sortie')
                      ->comment('Mode de paiement: comptant ou crédit');
            }
            
            if (!Schema::hasColumn('sorties', 'credit_id')) {
                $table->foreignId('credit_id')
                      ->nullable()
                      ->after('payment_mode')
                      ->constrained('credits')
                      ->onDelete('set null')
                      ->comment('Lien vers le crédit si payment_mode = credit');
            }
            
            if (!Schema::hasColumn('sorties', 'created_by')) {
                $table->foreignId('created_by')
                      ->nullable()
                      ->after('user_id')
                      ->constrained('users')
                      ->onDelete('set null')
                      ->comment('Utilisateur qui a créé la sortie');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sorties', function (Blueprint $table) {
            $table->dropForeign(['credit_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn(['payment_mode', 'credit_id', 'created_by']);
            
            // استعادة الاسم القديم إذا كنت تريد التراجع
            if (Schema::hasColumn('sorties', 'total_price')) {
                $table->renameColumn('total_price', 'prix_total');
            }
        });
    }
};