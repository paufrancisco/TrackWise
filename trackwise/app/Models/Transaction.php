<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory;

    /** 
     * @var array<string> The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'amount',
        'category',
        'description',
        'transaction_date',
    ];

    /** 
     * @var array<string, string> The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Get the user that owns the transaction.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only income transactions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope a query to only expense transactions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', 'expense');
    }
}