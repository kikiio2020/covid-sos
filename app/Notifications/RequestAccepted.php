<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\SosRequest;
use Carbon\Carbon;

class RequestAccepted extends Notification
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
        $isHujo = $this->sosRequest->is_hujo;
        
        $line2 = '';
        if ($isResponder) {
            //Responder
            $line = 
                '**' . $this->sosRequest->user->name . '**'
                . ' has just accepted your pledge to their request '
                . '**"' . $this->sosRequest->sos->name . '"**'
                . ' scheduled for '
                . '**' . Carbon::parse($this->sosRequest->needed_by)->format('M d, Y') . '**'
                . '. Great! You can start communicating by clicking the button below.';
            if ($isHujo) {
                $line2 = 'As both you and '
                    . $this->sosRequest->responder->name
                    . ' are on the Hujo Coin network, you will recieve one Hujo Coin from '
                    . $this->sosRequest->responder->name 
                    . ' at the completion of the request.'; 
            }
        } else {
            //Requestor
            $line = 'You have just accepted '
                . '**' . $this->sosRequest->responder->name . '**'
                . '\'s pledge for your request '
                . '**"' . $this->sosRequest->sos->name . '"**'
                . ' scheduled for '
                . '**' . Carbon::parse($this->sosRequest->needed_by)->format('M d, Y') . '**'
                . '. Great! You can start communicating by clicking the button below.';
            if ($isHujo) {
                $line2 = 'As both you and ' 
                    . $this->sosRequest->responder->name
                    . ' are on the Hujo Coin network, you will be prompted to exchange one Hujo Coin with '
                    . $this->sosRequest->responder->name 
                    . ' at the completion of the request.';
            }
        }
        
        $mailMessage = (new MailMessage)
            ->subject(config('mail.subjectPrefix') . 'Request **' . $this->sosRequest->sos->name . '** Accepted')
            ->greeting('Hi ' . $notifiable->name)
            ->line($line);
        if ($line2) {
            $mailMessage->line($line2);
        }
        $mailMessage->action('Start Communication', url('sosRequest/' . $this->sosRequest->id . '/inProgress/'))
            ->line('Thank you for being part of our community!')
            ->line("Sincerely,")
            ->salutation(config('mail.notificationSignature'));
        
        return $mailMessage;
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
