<?php

namespace App\Services;

use App\Repositories\TicketRepositoryInterface;
use App\Repositories\CustomerRepositoryInterface;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Exception;

class TicketService
{
    protected TicketRepositoryInterface $tickets;
    protected CustomerRepositoryInterface $customers;
    
    public function __construct(TicketRepositoryInterface $tickets, CustomerRepositoryInterface $customers)
    {
        $this->tickets = $tickets;
        $this->customers = $customers;
    }
    public function createTicket(array $payload, array $uploadedFiles = []): Ticket
    {
        $phone = $payload['phone'] ?? null;
        $email = $payload['email'] ?? null;
        
        // limit: one per 24 hours by phone or email
        $now = Carbon::now();
        $since = $now->subDay();
        
        $recentQuery = Ticket::whereHas('customer', function($q) use ($phone, $email) {
            if ($phone) $q->where('phone', $phone);
            if ($email) $q->orWhere('email', $email);
        })->where('created_at', '>=', $since);
        
        if ($recentQuery->exists()) {
            throw new \DomainException('You can submit only one ticket per 24 hours.');
        }
        
        return DB::transaction(function () use ($payload, $uploadedFiles, $phone, $email) {
            $customer = $this->customers->firstOrCreateByContact([
                'name' => $payload['name'],
                'phone' => $phone,
                'email' => $email,
            ]);
            
            $ticket = $this->tickets->create([
                'customer_id' => $customer->id,
                'subject' => $payload['subject'],
                'body' => $payload['body'],
                'status' => 'new',
            ]);
            
            foreach ($uploadedFiles as $file) {
                if ($file instanceof UploadedFile) {
                    $ticket->addMedia($file)->toMediaCollection('attachments');
                }
            }
            
            return $ticket;
        });
    }
    
    public function getStatisticsForPeriod(string $period): int
    {
        $now = Carbon::now();
        return match ($period) {
            'day' => $this->tickets->statistics($now->copy()->startOfDay(), $now->copy()->endOfDay()),
            'week' => $this->tickets->statistics($now->copy()->subWeek()->startOfDay(), $now->copy()->endOfDay()),
            'month' => $this->tickets->statistics($now->copy()->subMonth()->startOfDay(), $now->copy()->endOfDay()),
            default => 0
        };
    }
}
