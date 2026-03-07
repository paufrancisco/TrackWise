<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['income', 'expense']);
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->string('category');
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};