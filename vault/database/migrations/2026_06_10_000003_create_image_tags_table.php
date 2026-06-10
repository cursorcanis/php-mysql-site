<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Pivot table for the File <-> Tag many-to-many.
// Column names kept as image_id/tag_id to match the original schema.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('image_tags', function (Blueprint $table) {
            $table->foreignId('image_id')->constrained('files')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->primary(['image_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_tags');
    }
};
