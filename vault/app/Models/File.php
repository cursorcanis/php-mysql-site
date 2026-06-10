<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class File extends Model
{
    // We manage a single `uploaded_at` column instead of created_at/updated_at.
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'file_size'   => 'integer',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'image_tags', 'image_id', 'tag_id');
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}
