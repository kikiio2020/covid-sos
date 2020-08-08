<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\HujoCoin;

class HujoCoinEnrolled extends Notification
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
            ->subject(config('mail.subjectPrefix') . 'Hujo Coin Enrollment')
        ->greeting('Hi ' . $notifiable->name)
        ->line(
            'Congratulation! And welcome! You are now part of the Hujo Coin community.'
            . 'You can now show your gratitude to your helper by exchanging the Hujo Coin, and vice versa.'
        )
        ->line(
            'For your record please keep this transaction ID for future reference.'
        )
        ->line(
            'Transaction ID: '
            . '**' . $this->hujoCoin->hujoCoinTx->transaction_hash . '**  '
            . 'Wallet ID: '
            . '**' . $this->hujoCoin->crypto_address . '**'
        )
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
