<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('image')->nullable()->after('id');
            $table->bigInteger('phone')->nullable()->after('password');
            $table->string('location')->nullable()->after('phone');
            $table->string('about_me')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('phone');
            $table->dropColumn('location');
            $table->dropColumn('about_me');
        });
    }
};
