<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================
        // 1. إضافة user_id لجدول products
        // ============================================
        if (!Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->after('id')
                      ->nullable()  // nullable مؤقتاً
                      ->constrained('users')
                      ->onDelete('cascade');
            });
            
            // ✅ تعيين user_id للبيانات الموجودة
            // (افتراض: أول مستخدم)
            $firstUserId = DB::table('users')->orderBy('id')->value('id');
            if ($firstUserId) {
                DB::table('products')
                  ->whereNull('user_id')
                  ->update(['user_id' => $firstUserId]);
            }
            
            // جعل العمود NOT NULL بعد التعبئة
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        // ============================================
        // 2. إضافة user_id لجدول clients
        // ============================================
        if (!Schema::hasColumn('clients', 'user_id')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->after('id')
                      ->nullable()
                      ->constrained('users')
                      ->onDelete('cascade');
            });
            
            $firstUserId = DB::table('users')->orderBy('id')->value('id');
            if ($firstUserId) {
                DB::table('clients')
                  ->whereNull('user_id')
                  ->update(['user_id' => $firstUserId]);
            }
            
            Schema::table('clients', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        // ============================================
        // 3. التحقق من user_id في credits
        // ============================================
        if (!Schema::hasColumn('credits', 'user_id')) {
            Schema::table('credits', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->after('client_id')
                      ->nullable()
                      ->constrained('users')
                      ->onDelete('cascade');
            });
            
            // تعيين user_id من created_by إذا كان موجوداً
            DB::statement('UPDATE credits SET user_id = created_by WHERE user_id IS NULL');
            
            Schema::table('credits', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }

        // ============================================
        // 4. التحقق من user_id في sorties
        // ============================================
        if (!Schema::hasColumn('sorties', 'user_id')) {
            Schema::table('sorties', function (Blueprint $table) {
                $table->foreignId('user_id')
                      ->after('credit_id')
                      ->nullable()
                      ->constrained('users')
                      ->onDelete('cascade');
            });
            
            // تعيين user_id من created_by إذا كان موجوداً
            DB::statement('UPDATE sorties SET user_id = created_by WHERE user_id IS NULL AND created_by IS NOT NULL');
            
            $firstUserId = DB::table('users')->orderBy('id')->value('id');
            if ($firstUserId) {
                DB::table('sorties')
                  ->whereNull('user_id')
                  ->update(['user_id' => $firstUserId]);
            }
            
            Schema::table('sorties', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('credits', function (Blueprint $table) {
            if (Schema::hasColumn('credits', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('sorties', function (Blueprint $table) {
            if (Schema::hasColumn('sorties', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};