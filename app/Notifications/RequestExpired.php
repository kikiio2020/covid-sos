<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\SosRequest;

class RequestExpired extends Notification
{
    use Queueable;

    private $sosRequest;    
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SosRequest $sosRequest)
    {
        $this->sosRequest = $sosRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject(config('mail.subjectPrefix') . 'Request <' . $this->sosRequest->sos->name . '> is Expired')
        ->greeting('Hi ' . $notifiable->getUserName())
        ->line('Unfortunately your request has expired without any taker.')
        ->line($this->sosRequest->user->getUserName())
        ->line('Please send out a new request for a new date')
        ->line('Thank you for being part of our community!')
        ->line("Sincerely,")
        ->salutation(config('mail.notificationSignature'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
