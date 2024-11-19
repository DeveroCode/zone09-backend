<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'last_name',
        'folio',
        'computer_id',
        'phone',
        'start',
        'end',
        'total_hours',
        'total_amount',
        'loan'
    ];

    public function computers()
    {
        return $this->belongsTo(Computer::class, 'computer_id');
    }
    
}