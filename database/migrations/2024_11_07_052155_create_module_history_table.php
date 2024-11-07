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
        Schema::create('module_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules');     
            $table->float('measured_value');
            $table->string('status');
            $table->integer('operating_time');
            $table->integer('data_sent_count');
            $table->timestamp('recorded_at')->default(now()); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_history');
    }
};
