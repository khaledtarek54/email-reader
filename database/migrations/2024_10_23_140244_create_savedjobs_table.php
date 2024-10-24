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
        Schema::create('savedjobs', function (Blueprint $table) {
            $table->id();
            $table->string('mail_id')->nullable();
            $table->string('source_language')->nullable();
            $table->string('target_language')->nullable();
            $table->string('job_type')->nullable();
            $table->float('amount')->nullable();
            $table->string('unit')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('delivery_time')->nullable();
            $table->string('delivery_timezone')->nullable();
            $table->text('shared_instructions')->nullable();
            $table->float('unit_price')->nullable();
            $table->string('currency')->nullable();
            $table->json('in_folder')->nullable();
            $table->json('instructions_folder')->nullable();
            $table->json('reference_folder')->nullable();
            $table->boolean('online_source_files')->nullable();
            $table->string('content_type')->nullable();
            $table->string('subject_matter')->nullable();
            $table->string('auto_plan_strategy')->nullable();
            $table->string('auto_assignment')->nullable();
            $table->string('selection_plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savedjobs');
    }
};
