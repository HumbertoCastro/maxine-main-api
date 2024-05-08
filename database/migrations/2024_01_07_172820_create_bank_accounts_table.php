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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->default(md5(uniqid(rand() . "", true)));
            $table->string('account_number')->unique();
            $table->string('account_type');
            $table->string('account_holder');
            $table->string('cpf_cnpj')->unique();
            $table->string('bank');
            $table->string('agency');
            $table->decimal('balance', 10, 2)->default(0);
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
        Schema::dropIfExists('bank_accounts');
    }
};
