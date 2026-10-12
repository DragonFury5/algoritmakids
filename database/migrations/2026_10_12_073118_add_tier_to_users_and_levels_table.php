<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tier')->default('rookie')->after('role');
        });

        Schema::table('levels', function (Blueprint $table) {
            $table->string('tier')->default('rookie')->after('module_id');
            $table->index('tier');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tier');
        });

        Schema::table('levels', function (Blueprint $table) {
            $table->dropIndex(['tier']);
            $table->dropColumn('tier');
        });
    }
};