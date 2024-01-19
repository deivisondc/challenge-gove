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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('file_import_id');
            $table->date('scheduled_for');
            $table->string('status');
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('file_import_id')->references('id')->on('file_imports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
