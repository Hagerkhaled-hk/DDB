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
      /*   Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('grade');
            $table->timestamps();
        }); */
        Schema::connection("branch_A")->create('students', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("branch")->default("branch_A");
            $table->string('name');
            $table->string('grade');
            $table->timestamps();
        });
        Schema::connection("branch_B")->create('students', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("branch")->default("branch_B");
            $table->string('name');
            $table->string('grade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    Schema::connection('branch_A')->dropIfExists('students');
    Schema::connection('branch_B')->dropIfExists('students');
        }
};
