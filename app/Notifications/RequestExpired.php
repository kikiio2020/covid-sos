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
        $isResponder = $notifiable->id === $this->sosRequest->responded_by;
        
        if ($isResponder) {
            $line1 = 'The person whom you wanted to help for the following request has not responded.';
            $line2 = 'The request is now expired and will be removed from the list';
        } else {
            $line1 = 'Unfortunately your request has expired without any taker.';
            $line2 = 'You can send out a new request with a new date';
        }
        
        
        return (new MailMessage)
        ->subject(config('mail.subjectPrefix') . 'Request <' . $this->sosRequest->sos->name . '> is Expired')
        ->greeting('Hi ' . $notifiable->getUserName())
        ->line($line1)
        ->line($this->sosRequest->user->getUserName())
        ->line($line2)
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
