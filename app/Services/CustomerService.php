<?php

namespace App\Services;

use App\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerService
{
    protected CustomerRepositoryInterface $customers;
    
    public function __construct(CustomerRepositoryInterface $customers)
    {
        $this->customers = $customers;
    }
    public function firstOrCreateByContact(array $data): Customer
    {
        return Customer::firstOrCreate(
            ['email' => $data['email'], 'phone' => $data['phone'] ?? null],
            ['name' => $data['name'] ?? 'Unknown']
        );
    }
    public function newCustomersCount(Carbon $from, Carbon $to): int
    {
        return Customer::whereBetween('created_at', [$from, $to])->count();
    }
    public function paginate(array $filters = [], int $perPage = 15)
    {
        return $this->customers->paginate($filters, $perPage);
    }
}
