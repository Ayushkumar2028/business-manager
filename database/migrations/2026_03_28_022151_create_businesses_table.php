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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->unsignedBigInteger('duplicate_group_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
