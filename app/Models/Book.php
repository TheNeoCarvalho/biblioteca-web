<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'publication_year',
        'total_quantity',
        'available_quantity'
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'total_quantity' => 'integer',
        'available_quantity' => 'integer'
    ];

    /**
     * Get the loans for the book.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Check if the book is available for loan.
     */
    public function isAvailable(): bool
    {
        return $this->available_quantity > 0;
    }

    /**
     * Check if the book has low stock (less than 2 units available).
     */
    public function hasLowStock(): bool
    {
        return $this->available_quantity < 2;
    }

    /**
     * Get the number of books currently on loan.
     */
    public function getBooksOnLoan(): int
    {
        return $this->total_quantity - $this->available_quantity;
    }
}
