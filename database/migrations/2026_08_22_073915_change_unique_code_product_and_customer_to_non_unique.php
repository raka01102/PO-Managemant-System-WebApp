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
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_name_unique');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->Unique('name', 'products_name_unique');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->Unique('name', 'customers_name_unique');
        });
    }
};
