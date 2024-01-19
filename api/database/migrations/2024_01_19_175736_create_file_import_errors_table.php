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
        Schema::create('file_import_errors', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('file_import_id');
            $table->string('error');
            $table->timestamps();

            $table->foreign('file_import_id')->references('id')->on('file_import');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_import_errors');
    }
};
