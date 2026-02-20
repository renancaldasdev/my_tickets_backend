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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('description');

            $table->foreignId('status_id')->constrained('status');
            $table->foreignId('priority_id')->constrained('priorities');
            $table->foreignId('business_unit_id')->constrained('business_units');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('customer_id')->constrained('customers');

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('agent_id')->nullable()->constrained('users');

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
