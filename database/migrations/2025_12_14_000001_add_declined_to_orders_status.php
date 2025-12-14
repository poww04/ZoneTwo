<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include 'declined'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'cancelled', 'declined', 'confirm', 'on deliver', 'complete') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum (without declined)
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'cancelled', 'confirm', 'on deliver', 'complete') DEFAULT 'pending'");
    }
};
