<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'is_returned',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'is_returned' => 'boolean',
    ];

    /**
     * Get the student that owns the loan.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the book that is loaned.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the loan is overdue.
     */
    public function isOverdue(): bool
    {
        if ($this->is_returned) {
            return false;
        }

        return Carbon::now()->isAfter($this->due_date);
    }

    /**
     * Get the number of days the loan is overdue.
     * Returns 0 if not overdue.
     */
    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->due_date);
    }

    /**
     * Get the number of days until the loan is due.
     * Returns negative number if overdue.
     */
    public function getDaysUntilDue(): int
    {
        if ($this->is_returned) {
            return 0;
        }

        return $this->due_date->diffInDays(Carbon::now(), false);
    }

    /**
     * Check if the loan was returned late.
     */
    public function wasReturnedLate(): bool
    {
        if (!$this->is_returned || !$this->return_date) {
            return false;
        }

        return $this->return_date->isAfter($this->due_date);
    }

    /**
     * Scope to get active loans (not returned).
     */
    public function scopeActive($query)
    {
        return $query->where('is_returned', false);
    }

    /**
     * Scope to get overdue loans.
     */
    public function scopeOverdue($query)
    {
        return $query->active()->where('due_date', '<', Carbon::now());
    }

    /**
     * Scope to get returned loans.
     */
    public function scopeReturned($query)
    {
        return $query->where('is_returned', true);
    }
}
