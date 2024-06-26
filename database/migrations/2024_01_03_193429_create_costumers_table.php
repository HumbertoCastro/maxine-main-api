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
        Schema::create('costumers', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->default(md5(uniqid(rand() . "", true)));
            $table->string('name');
            $table->string('email');
            $table->bigInteger('phone');
            $table->date('birth_date');
            $table->string('sex');
            $table->bigInteger('cpf');
            $table->string('address');
            $table->string('district');
            $table->string('city');
            $table->integer('cep');
            $table->string('uf');
            $table->boolean('is_active');
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
        Schema::dropIfExists('costumers');
    }
};
