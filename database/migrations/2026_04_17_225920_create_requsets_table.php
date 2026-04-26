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
     /*     Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('from_branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('to_branch_id')->constrained('branches')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        }); */
         Schema::connection("branch_A")->create('requests', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('student_id');
            $table->bigInteger('from_branch_id');
            $table->bigInteger('to_branch_id');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
         Schema::connection("branch_B")->create('requests', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('student_id');
            $table->bigInteger('from_branch_id');
            $table->bigInteger('to_branch_id');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::connection('branch_A')->dropIfExists('requests');
    Schema::connection('branch_B')->dropIfExists('requests');

}
};
