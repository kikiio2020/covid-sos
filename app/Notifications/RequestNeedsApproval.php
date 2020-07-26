<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\SosRequest;

class RequestNeedsApproval extends Notification
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
            $line1 = $this->sosRequest->user->getUserName() . ' ' .
                'has indicated the work for their request ' .
                '<' . $this->sosRequest->sos->name . '> is now completed. Thanks!';
            $line2 = 'Please confirm and close the request by following the link below' .
                'to the request and click the \'Complete\' button.';
        } else {
            $line1 = 'Good news! ' . $this->sosRequest->responder->getUserName() . ' ' .
                'has now completed the work for your request ' .
                '<' . $this->sosRequest->sos->name . '> ';
            $line2 = 'Please follow the link below to the request and click the ' .
                '\'Complete\' button to confirm and close the request.';
        }
        
        $subject = config('mail.subjectPrefix') . 'Re: Request <' . $this->sosRequest->sos->name . '>';
        
        $url = url('sosRequest/' . $this->sosRequest->id . '/inProgress/');
        
        //framework/src/Illuminate/Notifications/resources/views/email.blade.php
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hi ' . $notifiable->getUserName())
            ->line($line1)
            ->line($line2)
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
