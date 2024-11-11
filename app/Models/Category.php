<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes included if required

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'slug'
    ];

    // Automatically generate and update the slug based on the name
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relation with Product model
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
