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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clients_id')->constrained()->onDelete('cascade');
            $table->date('due_date');
            $table->decimal('amount', 10, 2);
            $table->date('rcd_due_date')->nullable();
            $table->decimal('rcd_amount', 10, 2)->nullable();
            $table->decimal('bad_debt_amount', 10)->nullable();
            $table->string('invoice_year', 7);
            $table->integer('time_gap')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['bad_debts', 'good'])->nullable();
            $table->enum('payment_type', ['bank', 'cash'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
