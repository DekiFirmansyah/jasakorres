<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Letter;
use Twilio\Rest\Client;

class LetterValidationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $letter;

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
                    ->action('Lihat Surat', url('/validations/'))
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
            'url' => '/validations/',
            'message' => 'Surat perlu divalidasi!',
            'title' => $this->letter->title,
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
            'url' => '/validations/',
            'message' => 'Surat perlu divalidasi!',
            'title' => $this->letter->title,
        ]);
    }

    public function broadcastOn()
    {
        return ['letter-validation'];
    }

    // public function toWhatsApp($notifiable)
    // {
    //     $sid = env('TWILIO_SID');
    //     $token = env('TWILIO_TOKEN');
    //     $twilio = new Client($sid, $token);

    //     $message = "Ada surat baru yang perlu Anda validasi.\nJudul: " . $this->letter->title . "\n\nLihat surat: " . url('/validations/');

    //     $twilio->messages->create(
    //         'whatsapp:+6281331386946',
    //         [
    //             'from' => 'whatsapp:' . env('TWILIO_WHATSAPP_NUMBER'),
    //             'body' => $message
    //         ]
    //     );
    // }
}