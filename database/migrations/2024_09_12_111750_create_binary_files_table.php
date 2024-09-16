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
        Schema::create('binary_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->index();
            $table->foreignId('binary_type_id')->constrained('binary_types')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_format');
            $table->binary('binary_data')->nullable(); // バイナリデータを一時的にBLOB型で保存
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'binary_type_id']);
        });

        DB::statement('ALTER TABLE binary_files MODIFY binary_data MEDIUMBLOB;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Sche