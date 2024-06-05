<?php

namespace App\Notifications;

use App\Models\Letter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;


class UpdateLetterNotification extends Notification
{
    use Queueable;

    public $letter;

    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Ada surat yang perlu Anda perbarui.')
                    ->action('Lihat Surat', url('/letters/' . $this->letter->id))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'url' => '/letters/',
            'message' => 'Surat perlu diperbarui!',
            'title' => $this->letter->title,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'url' => '/letters/',
            'message' => 'Surat perlu diperbarui!',
            'title' => $this->letter->title,
        ]);
    }
}