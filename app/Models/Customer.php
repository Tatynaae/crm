<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'email', 'phone'];
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function scopeByContact($query, $phone = null, $email = null)
    {
        if ($phone) {
            $query->where('phone', $phone);
        }
        if ($email) {
            $query->where('email', $email);
        }
        
        return $query;
    }
}
