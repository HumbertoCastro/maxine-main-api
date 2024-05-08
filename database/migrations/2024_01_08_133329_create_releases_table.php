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
        Schema::create('releases', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('account_uuid');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['income', 'expense']);
            $table->text('description')->nullable();
            $table->uuid('company_uuid');
            $table->uuid('user_uuid');
            $table->uuid('payment_method_uuid');
            $table->timestamps();

            $table->foreign('company_uuid')
            ->references('uuid')
            ->on('companies');

            $table->foreign('account_uuid')
            ->references('uuid')
            ->on('bank_accounts');

            $table->foreign('user_uuid')
            ->references('uuid')
            ->on('users');

            $table->foreign('payment_method_uuid')
            ->references('uuid')
            ->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
