<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $type;
    public $mensaje;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $type, $mensaje )
    {
        $this->type = $type;
        $this->mensaje = $mensaje;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        /*// Obtén el token del bot desde las variables de entorno
        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) {
            throw new \Exception('TELEGRAM_BOT_TOKEN no está configurado en el archivo .env.');
        }

        // Define el chat ID basado en el tipo
        $group = '';
        if ($this->type == 'process') {
            $group = env('TELEGRAM_CHAT_ID_PROCESS');
        } else {
            throw new \Exception("Tipo de notificación no soportado: {$this->type}");
        }

        if (!$group) {
            throw new \Exception("TELEGRAM_CHAT_ID_PROCESS no está configurado en el archivo .env.");
        }

        // Construye el mensaje
        $message = $this->mensaje;

        // Construye la URL para la API de Telegram
        $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

        // Define los parámetros del mensaje
        $params = [
            'chat_id' => $group,
            'text' => $message,
        ];

        // Enviar la solicitud a Telegram usando file_get_contents
        $response = file_get_contents($apiUrl . '?' . http_build_query($params));

        // Devuelve la respuesta de Telegram (opcional para depuración)
        return json_decode($response, true);*/
        // Obtén el token del bot y el chat ID desde las variables de entorno
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $group = '';

        // Determina el grupo basado en el tipo de notificación
        if ($this->type == 'process') {
            $group = env('TELEGRAM_CHAT_ID_PROCESS');
        }

        if (empty($group)) {
            throw new \Exception("El chat ID de Telegram no está configurado para el tipo {$this->type}.");
        }

        // Construye el mensaje dinámico
        return TelegramMessage::create()
            ->to($group)
            ->content($this->mensaje);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
