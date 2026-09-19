<?php

namespace App\Observers;

use App\Models\User;
use App\Services\SmsService;

class UserObserver
{
    public function __construct(
        private readonly SmsService $smsService,
    ) {
    }

    public function updated(User $user): void
    {
        if (
            $user->wasChanged('approval_status')
            && $user->getOriginal('approval_status') === 'pending'
            && $user->approval_status === 'approved'
        ) {
            $this->smsService->sendAccountApproval($user);
        }
    }
}
