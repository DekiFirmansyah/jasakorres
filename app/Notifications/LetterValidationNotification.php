<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Letter;

class LetterValidationNotification extends Notification
{
    use Queueable;

    protected $letter;

    /**
     * Create a new notification instance.
     */
    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Ada surat baru yang perlu Anda validasi.')
                    ->action('Lihat Surat', url('/letters/' . $this->letter->id))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'letter_id' => $this->letter->id,
            'title' => $this->letter->title,
            'about' => $this->letter->about,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'letter_id' => $this->letter->id,
            'title' => $this->letter->title,
            'about' => $this->letter->about,
        ]);
    }
}