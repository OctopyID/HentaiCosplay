<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubPage extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'hash', 'url', 'status'
    ];

    /**
     * @return string
     */
    public function getCacheAttribute() : string
    {
        return $this->hash . '.html';
    }

    /**
     * @return BelongsTo
     */
    public function main() : BelongsTo
    {
        return $this->belongsTo(MainPage::class, 'main_page_id');
    }

    /**
     * @return HasMany
     */
    public function images() : HasMany
    {
        return $this->hasMany(Image::class, 'sub_page_id');
    }
}
