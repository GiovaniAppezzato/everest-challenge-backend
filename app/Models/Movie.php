<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'imdb_id',
        'title',
        'year',
        'poster',
        'released',
        'plot',
        'type',
        'runtime',
    ];

    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Director::class)
            ->withTimestamps();
    }
}
