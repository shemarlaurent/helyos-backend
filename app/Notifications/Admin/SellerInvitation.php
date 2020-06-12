<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerInvitation extends Notification
{
    use Queueable;

    private string $invitationToken;

    /**
     * Create a new notification instance.
     *
     * @param string $invitationToken
     */
    public function __construct(string $invitationToken)
    {
        $this->invitationToken = $invitationToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Invitation to Helyos')
            ->greeting('Hello,')
            ->line('You have been invited to start selling your products on')
            ->line('the Helyos platform')
            ->line('Use the invitation Token below to create a seller profile')
            ->line($this->invitationToken)
            ->line('or visit our website for more information');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
