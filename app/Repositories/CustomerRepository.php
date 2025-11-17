<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }
    
    public function find(int $id): ?Customer
    {
        return Customer::with('tickets')->find($id);
    }
    
    public function paginate(array $filters, int $perPage = 15)
    {
        $q = Customer::with('tickets');
        
        if (!empty($filters['name'])) {
            $q->where('name', 'like', '%'.$filters['name'].'%');
        }
        if (!empty($filters['email'])) {
            $q->where('email', $filters['email']);
        }
        if (!empty($filters['phone'])) {
            $q->where('phone', $filters['phone']);
        }
        if (!empty($filters['date_from'])) {
            $q->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $q->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        return $q->orderByDesc('created_at')->paginate($perPage);
    }
    
    public function count(): int
    {
        return Customer::count();
    }
}
