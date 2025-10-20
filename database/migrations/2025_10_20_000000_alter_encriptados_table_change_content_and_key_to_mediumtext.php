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
        Schema::table('encriptados', function (Blueprint $table) {
            $table->mediumText('content')->change();
            $table->mediumText('key')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encriptados', function (Blueprint $table) {
            $table->text('content')->change();
            $table->text('key')->change();
        });
    }
};
