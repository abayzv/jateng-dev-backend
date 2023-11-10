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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('address_name', 100);
            $table->string('recipient_name', 100)->nullable();
            $table->string('recipient_phone_number', 100)->nullable();
            $table->string('address_line_1', 100);
            $table->string('address_line_2', 100)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip_code', 100);
            $table->string('country', 100);
            $table->boolean('is_primary')->default(false);
            $table->string('latitude', 100);
            $table->string('longitude', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
