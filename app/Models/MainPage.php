<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainPage extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'title', 'url', 'hash', 'status'
    ];

    /**
     * @return string
     */
    public function getCacheAttribute() : string
    {
        return $this->hash . '.html';
    }

    /**
     * @return HasMany
     */
    public function pages() : HasMany
    {
        return $this->hasMany(SubPage::class, 'main_page_id');
    }
}
