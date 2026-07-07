<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_name',
        'borrow_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'borrowing_details')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
