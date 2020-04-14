<?php

namespace App\Models;

use App\Helpers\HasAuthor;
use App\Helpers\HasTags;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasAuthor, HasTags;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'title',
    ];

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
