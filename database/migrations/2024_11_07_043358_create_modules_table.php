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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('type'); 
            $table->float('measured_value')->nullable(); 
            $table->integer('operating_time')->default(0); 
            $table->integer('data_sent_count')->default(0); 
            $table->string('status')->default('active'); 
            $table->timestamp('last_operated_at')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
