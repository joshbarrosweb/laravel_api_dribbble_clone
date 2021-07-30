<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;

class ResetPassword extends Notification
{
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url').'/password/reset/'.$this->token).'?email='.urlencode($notifiable->email);

        return (new MailMessage)
                    ->line('You are receiving this email because we have received a password reset for your account')
                    ->action('Reset Password', $url)
                    ->line('If you did not request a password reset, no further action is necessary');
    }
}
