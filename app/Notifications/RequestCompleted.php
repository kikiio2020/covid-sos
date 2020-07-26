<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\SosRequest;

class RequestCompleted extends Notification
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
        
        $line = $isResponder ? 
            'Thank you being awesome! This is to confirm the request ' . 
            '<' . $this->sosRequest->sos->name . '> for ' .
            $this->sosRequest->user->getUserName() . 
            ' is now completed.'
            : 
            'This is to confirm your request "' . 
            '<' . $this->sosRequest->sos->name . '> ' . 
            '" is now completed.';
        
        $subject = config('mail.subjectPrefix') . 'Request <' . $this->sosRequest->sos->name . '> Compeleted';
        
        $url = url('sosRequest/' . $this->sosRequest->id . '/history/');
        
        //framework/src/Illuminate/Notifications/resources/views/email.blade.php
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hi ' . $notifiable->getUserName())
            ->line($line)
            ->line('For you reference, you can click the button below to access the request. It will remain there for next month.')
            ->action($this->sosRequest->sos->name, $url)
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
