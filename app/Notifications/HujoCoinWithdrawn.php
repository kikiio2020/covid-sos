<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class HujoCoinWithdrawn extends Notification
{
    use Queueable;

    private $balance;
    private $enrollmentDate;
    private $lastTransactionDate;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $balance, string $enrollmentDate, string $lastTransactionDate)
    {
        $this->balance = $balance;
        $this->enrollmentDate = $enrollmentDate;
        $this->lastTransactionDate = $lastTransactionDate;
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
        $accountDetails = 
            'Closing Date: **' . date('Y-m-d') . "**<br>"
            . 'Closing Balance: **' . $this->balance . '**<br>'
            . 'Date Joined: **' . $this->enrollmentDate . '**<br>'
            . 'Last Transaction Date: **' . $this->lastTransactionDate . '**<br>'
            . 'Wallet ID: **' . $notifiable->hujoCoin->crypto_address . '**';
        $mailMessage = (new MailMessage)
            ->subject(config('mail.subjectPrefix') . 'Hujo Coin Enrollment Withdrawn')
            ->greeting('Hi ' . $notifiable->name)
            ->line('At your request, you are now withdrawn from our Hujo Coin program.')
            ->line('We\'re sorry to see you go, but you can come back any time! '
                . 'For your record, here are the details of your Hujo Coin account at the time of closing: ')
            ->line(new HtmlString($accountDetails))
            ->line('You can still access your Hujo Coin page on our website, or just click the button below. '
                . 'Your balance will be kept minus any idle or annual fees since you were last here.')
            ->action('Your Hujo Coin Account', url('/hujoCoin'))
            ->line('Thank you and we hope to see you again!')
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
