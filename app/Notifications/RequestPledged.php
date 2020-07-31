<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\SosRequest;
use Carbon\Carbon;

class RequestPledged extends Notification
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
        ->subject(config('mail.subjectPrefix') . 'Request **' . $this->sosRequest->sos->name . '** Pledged')
            ->greeting('Hi ' . $notifiable->getUserName())
            ->line('Good news!')
            ->line(
                '**' . $this->sosRequest->responder->name . '**' 
                . ' has pledged to help with your request ' 
                . '**"' . $this->sosRequest->sos->name . '"**' 
                . ' scheduled for '
                . '**' . Carbon::parse($this->sosRequest->needed_by)->format('M d, Y') . '**'
                . '. Please accept their help by clicking the button below. '
                . (
                    $notifiable->hujoCoin ? 
                        'As both you and ' 
                        . $this->sosRequest->responder->name
                        . ' are both on the Hujo Coin network, you will be prompted to exchange one Hujo coin to ' 
                        . $this->sosRequest->responder->name
                        . ' when the task is completed.' 
                    : ''
                  )
            )
            ->action('Accept', url('sosRequest/' . $this->sosRequest->id . '/accept/'))
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
