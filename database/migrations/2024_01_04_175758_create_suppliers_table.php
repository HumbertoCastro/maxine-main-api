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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->default(md5(uniqid(rand() . "", true)));
            $table->string('name');
            $table->bigInteger('phone');
            $table->string('email');
            $table->bigInteger('cnpj');
            $table->string('address');
            $table->integer('number');
            $table->string('district');
            $table->string('city');
            $table->string('uf');
            $table->integer('cep');
            $table->string('observation')->nullable();
            $table->uuid('company_uuid');
            $table->boolean('is_active');
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
        Schema::dropIfExists('suppliers');
    }
};
