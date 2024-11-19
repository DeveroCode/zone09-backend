<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
}