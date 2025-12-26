<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
            $table->boolean('is_active')->default(false)->after('is_admin');
            $table->enum('subscription_type', ['monthly', 'lifetime'])->nullable()->after('is_active');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_type');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'is_active', 'subscription_type', 'subscription_expires_at']);
        });
    }
};