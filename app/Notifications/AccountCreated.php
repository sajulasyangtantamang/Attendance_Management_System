<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification
{
    use Queueable;

    public function __construct(
        protected string $roleLabel,
        protected string $temporaryPassword,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your '.$this->roleLabel.' account has been created')
            ->greeting('Welcome, '.$notifiable->name.'!')
            ->line('An administrator has created a '.$this->roleLabel.' account for you on '.config('app.name').'.')
            ->line('Here are your login details:')
            ->line('**Email:** '.$notifiable->email)
            ->line('**Temporary password:** '.$this->temporaryPassword)
            ->line('For security, you will be required to set a new password immediately after your first login.')
            ->action('Log In', route('login'))
            ->line('If you were not expecting this account, please contact the administrator.');
    }
}
