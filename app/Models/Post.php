<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Comment;
// use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'category_id',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($post) {
            $post->slug = $post->createSlug($post->title);
            $post->save();
        });

        // static::deleting(function ($post) {
        //     $post->comments()->delete();
        //     $post->tags()->detach();
        // });
    }

    private function createSlug($title){
        if (static::whereSlug($slug = Str::slug($title))->exists()) {

            $max = static::whereTitle($title)->latest('id')->skip(1)->value('slug');

            if (is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function ($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }

    public function limit()
    {
        return Str::limit($this->description, YourClass::LIMIT );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function tags()
    // {
    //     return $this->belongsToMany(Tag::class)->withTimestamps();
    // }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDrafted($query)
    {
        return $query->where('is_published', false);
    }

    public function getPublishedAttribute()
    {
        return ($this->is_published) ? 'Yes' : 'No';
    }

    public function getEtagAttribute()
    {
        return hash('sha256', "product-{$this->id}-{$this->updated_at}");
    }
}
