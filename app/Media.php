<?php

namespace App;

use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $touches = ['project'];

    protected $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return $this->getFullUrl();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
