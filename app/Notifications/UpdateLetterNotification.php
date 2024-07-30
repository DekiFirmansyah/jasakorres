<?php

namespace App\Notifications;

use App\Models\Letter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

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
                    ->action('Lihat Surat', url(route('letters.show', $this->letter->id)))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'url' => route('letters.show', $this->letter->id),
            'message' => 'Surat perlu diperbarui!',
            'title' => $this->letter->title,
            'url_data' => '/letters/',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'url' => route('letters.show', $this->letter->id),
            'message' => 'Surat perlu diperbarui!',
            'title' => $this->letter->title,
            'url_data' => '/letters/',
        ]);
    }

    public function broadcastOn()
    {
        return ['update-letter'];
    }

    // public function toWhatsApp($notifiable)
    // {
    //     $sid = env('TWILIO_SID');
    //     $token = env('TWILIO_TOKEN');
    //     $twilio = new Client($sid, $token);

    //     $message = "Ada surat yang perlu anda perbarui sesuai catatan!\nJudul: " . $this->letter->title . "\n\nLihat surat: " . url('/letters/');

    //     $twilio->messages->create(
    //         'whatsapp:' . $notifiable->phone,
    //         [
    //             'from' => 'whatsapp:' . env('TWILIO_WHATSAPP_NUMBER'),
    //             'body' => $message
    //         ]
    //     );
    // }
}