<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\SosRequest;
use Carbon\Carbon;

class PledgeCancelled extends Notification
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
        ->subject(config('mail.subjectPrefix') . 'Pledge for **' . $this->sosRequest->sos->name . '** Cancelled')
            ->greeting('Hi ' . $notifiable->name)
            ->line(
                'Unfortunately '
                . '**' . $this->sosRequest->responder->name . '**'
                . ' has cancelled their pledge for your request '
                . '**"' . $this->sosRequest->sos->name . '"**'
                . ' scheduled for '
                . '**' . Carbon::parse($this->sosRequest->needed_by)->format('M d, Y') . '**'
                . '. Please wait for another potential helper or try creating a different request.'
            )
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
