<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    /** 
     * @var array<string> The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'color',
    ];

    /**
     * Get the user that owns the category.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}