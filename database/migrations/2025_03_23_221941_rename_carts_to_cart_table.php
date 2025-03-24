<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename table carts ➝ cart
     */
    public function up(): void
    {
        Schema::rename('carts', 'cart');
    }

    /**
     * Rollback: Rename table cart ➝ carts
     */
    public function down(): void
    {
        Schema::rename('cart', 'carts');
    }
};
