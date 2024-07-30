<?php

namespace App\Notifications;

use App\Models\Letter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class RequestLetterCodeNotification extends Notification
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
                    ->line('Ada surat yang perlu Anda berikan nomor surat.')
                    ->action('Memberikan nomor surat', url('/validations/'))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'url' => route('validations.code', $this->letter->id),
            'message' => 'Surat perlu diberikan nomor surat!',
            'title' => $this->letter->title,
            'url_data' => '/validations/',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'url' => route('validations.code', $this->letter->id),
            'message' => 'Surat perlu diberikan nomor surat!',
            'title' => $this->letter->title,
            'url_data' => '/validations/',
        ]);
    }

    public function broadcastOn()
    {
        return ['request-letter-code'];
    }

    // public function toWhatsApp($notifiable)
    // {
    //     $sid = env('TWILIO_SID');
    //     $token = env('TWILIO_TOKEN');
    //     $twilio = new Client($sid, $token);

    //     $message = "Ada surat yang perlu diberikan nomor surat!\nJudul: " . $this->letter->title . "\n\nLihat surat: " . url('/validations/');

    //     $twilio->messages->create(
    //         'whatsapp:' . $notifiable->phone,
    //         [
    //             'from' => 'whatsapp:' . env('TWILIO_WHATSAPP_NUMBER'),
    //             'body' => $message
    //         ]
    //     );
    // }
}