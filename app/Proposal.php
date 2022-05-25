<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Rinvex\Categories\Traits\Categorizable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media as MediaItem;

class Proposal extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, HasRoles, HasMediaTrait, Categorizable;

    protected $with = ['user', 'media'];

    protected $location = 'public/uploads/';

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(255)
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'password', 'user_id', 'published_at'
    ];

    public function registerMediaConversions(MediaItem $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(800)
            ->height(800)
            ->sharpen(10);
    }

    /**
     * Get the user that created the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the polymorphic relation.
     *
     * @return mixed
     */
    public function media()
    {
        return $this->morphMany(config('medialibrary.media_model'), 'model')->orderBy('order_column');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function featuredImage()
    {
        return $this->hasOne(Asset::class);
    }

    public function getKeywords()
    {
        $allKeywords = Project::select('meta_keywords')->get();
        $keywords = array();
        foreach ($allKeywords as $k => $v) {
            $exploded = explode(',', $v->meta_keywords);
            foreach ($exploded as $key => $value) {
                if (!in_array($value, $keywords) && !empty($value)) {
                    $keywords[] = $value;
                }
            }
        }
        return $keywords;
    }

    public function getExcerpt($text = null)
    {
        $content = (empty($text)) ? $this->content : $text;
        return  str_limit(strip_tags($content), $limit = 160, $end = '...');
    }
    
    public function pathHash()
    {
        return sha1($this->id . $this->location);
    }
}
