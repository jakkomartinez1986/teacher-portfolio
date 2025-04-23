<?php

namespace App\Notifications;

use App\Models\Document\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $document;
    public $message;
    public $type;
    public $actionUrl;

    public function __construct(Document $document, $message, $type = 'info', $actionUrl = null)
    {
        $this->document = $document;
        $this->message = $message;
        $this->type = $type;
        $this->actionUrl = $actionUrl ?? route('documents.show', $document);
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Actualización de Documento')
                    ->line($this->message)
                    ->action('Ver Documento', $this->actionUrl);
    }

    public function toArray($notifiable)
    {
        return [
            'document_id' => $this->document->id,
            'message' => $this->message,
            'type' => $this->type,
            'action_url' => $this->actionUrl
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'id' => $this->id,
            'type' => get_class($this),
            'data' => $this->toArray($notifiable),
            'read_at' => null,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}