<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->default(md5(uniqid(rand() . "", true)));
            $table->string('name');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('payment_methods');
    }
};
