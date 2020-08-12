<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\HujoCoin;
use Illuminate\Support\HtmlString;

class HujoCoinReinstated extends Notification
{
    use Queueable;

    private $hujoCoin;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(HujoCoin $hujoCoin)
    {
        $this->hujoCoin = $hujoCoin;
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
        $mailMessage = (new MailMessage)
            ->subject(config('mail.subjectPrefix') . 'Hujo Coin Enrollment Reinstated')
        ->greeting('Hi ' . $notifiable->name)
        ->line(
            'Welcome back! We\'re glad you can join us again!'
        )
        ->line(
            'Just in case, here are your records for your Hujo Coin account again. '
            . 'Please keep them for future reference.'
        )
        ->line(new HtmlString(
            'Transaction ID: '
            . '**' . $this->hujoCoin->hujoCoinTx->transaction_hash . '**<br>'
            . 'Wallet ID: '
            . '**' . $this->hujoCoin->crypto_address . '**<br>'
        ))
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
