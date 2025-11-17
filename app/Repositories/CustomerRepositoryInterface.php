<?php

namespace App\Repositories;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function create(array $data): Customer;
    public function find(int $id): ?Customer;
    public function paginate(array $filters, int $perPage = 15);
    public function count(): int;
}
