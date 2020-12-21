<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;

class ApiNotifAuth extends Notification
{
    use Queueable;
    private $userData;

    public function __construct($userData)
    {
        $this->userData = $userData;
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
        $url = url('/api/actived/member/' . base64_encode($this->userData->email));
        $subject = "New Member";
        $greeting = 'Hello, ' . $this->userData->name;

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line('Silakan klik link di bawah ini untuk mengaktivasi akun Anda!')
            ->action('Klik Aktivasi', $url)
            ->line('Terimakasih, telah menggunakan aplikasi ini untuk kemudahan Anda bertransaksi!');
    }

    public function routeNotificationForMail()
    {
        return $this->email_address;
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
