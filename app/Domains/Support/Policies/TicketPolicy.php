<?php

namespace App\Domains\Support\Policies;

use App\Domains\Identity\Models\User;
use App\Domains\Support\Models\Ticket;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->customer_id !== $ticket->customer_id) {
            return false;
        }

        if ($user->hasRole('manager')) {
            return true;
        }

        if ($user->hasRole('agent')) {
            return $user->business_unit_id === $ticket->business_unit_id;
        }

        return $user->id === $ticket->user_id;
    }
}
