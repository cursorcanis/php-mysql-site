<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ports the `images` table from setup.sql + the Phase 1 `file_type` column.
// Renamed to `files` since the vault now stores documents/text too.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');                 // random hex name on disk
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedInteger('file_size');
            $table->enum('file_type', ['image', 'document', 'file'])->default('image');
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->string('share_token', 64)->nullable()->unique();
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
