<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model
{
    use HasFactory, InteractsWithMedia;
    
    protected $fillable = ['customer_id', 'subject', 'body', 'status', 'manager_replied_at'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function scopeCreatedBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
    
    public function scopeStatus($query, $status)
    {
        if ($status) $query->where('status', $status);
        return $query;
    }
}
