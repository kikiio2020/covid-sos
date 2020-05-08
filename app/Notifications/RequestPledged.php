<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Ask;

class RequestPledged extends Notification
{
    use Queueable;

    private $ask;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ask $ask)
    {
        $this->ask = $ask;
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
                    ->greeting('Hi ' . $notifiable->getUserName())
                    ->line('Good news!')
                    ->line($this->ask->responder->getUserName() . 
                        'has responded to your request <' .
                        $this->ask->sos->name . 
                        '> . You can start communicating with them by clicking the button below.'
                    )
                    ->action('Start Communication', url('ask/' . $this->ask->id . '/inProgress/'))
                    ->line('Thank you for being part of our community!')
                    ->line("Sincerely,")
                    ->salutation("Team " . config('app.name') . ' @ Kikiio');
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
