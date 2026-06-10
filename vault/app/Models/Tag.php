<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'image_tags', 'tag_id', 'image_id');
    }
}
