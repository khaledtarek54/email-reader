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
        Schema::table('savedjobs', function (Blueprint $table) {
            $table->string('mail_id_tp')->nullable();
            $table->boolean('mail_attachment_fetched')->defaultFalse();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savedjobs', function (Blueprint $table) {
            //
        });
    }
};
