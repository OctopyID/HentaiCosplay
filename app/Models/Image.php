<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'hash', 'url', 'status',
    ];

    /**
     * @return BelongsTo
     */
    public function page() : BelongsTo
    {
        return $this->belongsTo(SubPage::class, 'sub_page_id');
    }
}
