<?php

namespace App\Repositories;

use App\Models\Ticket;
use Carbon\Carbon;

interface TicketRepositoryInterface
{
    public function create(array $data): Ticket;
    public function find(int $id): ?Ticket;
    public function paginate(array $filters, int $perPage = 15);
    public function statistics(Carbon $from, Carbon $to): int;
}