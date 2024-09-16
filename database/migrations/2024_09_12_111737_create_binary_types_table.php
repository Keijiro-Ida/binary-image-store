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
        Schema::create('binary_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name');
            $table->string('type_name_jp');  // 日本語の種類名
            $table->string('description')->nullable();
            $table->string('description_jp')->nullable();  // 日本語の説明
            $table->string('field_name');  // ファイル名のフィールド名
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binary_types');
    }
};
