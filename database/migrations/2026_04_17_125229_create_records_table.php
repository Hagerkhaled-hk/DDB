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
        Schema::connection("branch_A")->create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('student_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->timestamps();

        });
        Schema::connection("branch_B")->create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('student_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::connection('branch_A')->dropIfExists('records');
    Schema::connection('branch_B')->dropIfExists('records');
    }
};
