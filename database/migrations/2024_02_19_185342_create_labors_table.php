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
        Schema::create('labors', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('office');
            $table->string('departament');
            $table->string('wage');
            $table->string('healthiness');
            $table->uuid('company_uuid');
            $table->timestamps();

            $table->foreign('company_uuid')
            ->references('uuid')
            ->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labors');
    }
};
