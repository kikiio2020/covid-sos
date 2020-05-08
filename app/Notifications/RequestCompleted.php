<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Ask;

class RequestCompleted extends Notification
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
        if (Ask::STATUS_COMPLETED !== $this->ask->status) {
            return;
        }
        
        $isResponder = $notifiable->id === $this->ask->responded_by;
        
        $line = $isResponder ? 
            'Thank you being awesome! This is to confirm the request ' . 
            '<' . $this->ask->sos->name . '> for ' .
            $this->ask->user->getUserName() . 
            ' is now completed.'
            : 
            'This is to confirm your request "' . 
            '<' . $this->ask->sos->name . '> ' . 
            '" is now completed.';
        
        $subject = config('mail.subjectPrefix') . 'Request <' . $this->ask->sos->name . '> Compeleted';
        
        //TODO construct URL to request in history
        $url = url('ask/' . $this->ask->id . '/history/');
        
        //framework/src/Illuminate/Notifications/resources/views/email.blade.php
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hi ' . $notifiable->getUserName())
            ->line($line)
            ->line('For you reference, you can click the button below to access the request. It will remain there for next month.')
            ->action($this->ask->sos->name, $url)
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
