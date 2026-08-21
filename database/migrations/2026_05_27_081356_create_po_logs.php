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
        Schema::create('po_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')
                ->constrained('purchase_orders')
                ->cascadeOnDelete();

            $table->string('type');
            // status / payment / attachment

            $table->string('old_value')->nullable();

            $table->string('new_value');

            $table->text('note')->nullable();

            $table->string('attachment')->nullable();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_logs');
    }
};
