<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
use App\SosRequest;

class HujoCoinExchanged extends Notification
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
        $hujoTxHash = $this->sosRequest->hujoCoinTx->transaction_hash;
        
        $line2 = '';
        if ($isResponder) {
            //Responder
            $line =
                '**' . $this->sosRequest->user->name . '**'
                . ' has just sent you one Hujo Coin to you for the request '
                . '**"' . $this->sosRequest->sos->name . '"**'
                . ' scheduled for '
                . '**' . Carbon::parse($this->sosRequest->needed_by)->format('M d, Y') . '**'
                . '. You will see the coin added to your balance momentarily. '
                . 'Please keep the following transaction ID for future reference: ';
                $line2 = '**' . $hujoTxHash . '**';
        } else {
            //Requestor
            $line =
                'This is to confirm you have just sent one Hujo Coin to '
                . '**' . $this->sosRequest->responder->name . '**'
                . ' for the request '
                . '**"' . $this->sosRequest->sos->name . '"**'
                . ' scheduled for '
                . '**' . Carbon::parse($this->sosRequest->needed_by)->format('M d, Y') . '**'
                . '. Please keep the following transaction ID for future reference: ';
                $line2 = '**' . $hujoTxHash . '**';
        }
        
        $mailMessage = (new MailMessage)
            ->subject(config('mail.subjectPrefix') . 'Hujo Coin Transfer for **' . $this->sosRequest->sos->name . '**')
            ->greeting('Hi ' . $notifiable->name)
            ->line($line)
            ->line($line2)
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
