<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your CMC Clinic Account Email')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Welcome to CMC School Clinic Management System.')
            ->line('Please verify your email address to activate your account and access the clinic portal.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not create this account, you can safely ignore this email.')
            ->salutation('Best regards, CMC Clinic Team');
    }
}