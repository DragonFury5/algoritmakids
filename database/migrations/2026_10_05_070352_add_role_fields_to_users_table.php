<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'parent', 'student'])->default('student')->after('email');
            $table->string('avatar')->default('robot-blue')->after('role');
            $table->enum('language', ['id', 'en'])->default('id')->after('avatar');
            $table->foreignId('parent_id')->nullable()->after('language')->constrained('users')->nullOnDelete();
            $table->timestamp('consent_given_at')->nullable()->after('parent_id');
            $table->boolean('sound_enabled')->default(true)->after('consent_given_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['role', 'avatar', 'language', 'parent_id', 'consent_given_at', 'sound_enabled']);
        });
    }
};