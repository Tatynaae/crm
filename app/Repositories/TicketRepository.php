<?php

namespace App\Repositories;

use App\Models\Ticket;
use Carbon\Carbon;

class TicketRepository implements TicketRepositoryInterface
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }
    
    public function find(int $id): ?Ticket
    {
        return Ticket::with('customer','media')->find($id);
    }
    
    public function paginate(array $filters, int $perPage = 15)
    {
        $q = Ticket::with('customer');
        
        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }
        if (!empty($filters['email'])) {
            $q->whereHas('customer', fn($q2)=>$q2->where('email', $filters['email']));
        }
        if (!empty($filters['phone'])) {
            $q->whereHas('customer', fn($q2)=>$q2->where('phone', $filters['phone']));
        }
        if (!empty($filters['date_from'])) {
            $q->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $q->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        return $q->orderByDesc('created_at')->paginate($perPage);
    }
    
    public function statistics(Carbon $from, Carbon $to): int
    {
        return Ticket::createdBetween($from, $to)->count();
    }
}
