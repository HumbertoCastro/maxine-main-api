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
        Schema::create('logistics', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('group');
            $table->string('category');
            $table->string('name');
            $table->string('description');
            $table->string('unit');
            $table->decimal('unitary_value', 10,2);
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
        Schema::dropIfExists('logistics');
    }
};
