<?php

namespace App\Livewire\Document;

use Livewire\Component;
use App\Models\Document\Document;
use App\Events\DocumentStatusUpdated;
use App\Events\DocumentSignatureUpdated;

class DocumentNotifications extends Component
{
    public $document;
    public $notifications = [];
    public $unreadCount = 0;

    // protected $listeners = [
    //     'echo-private:document.'.$document->id.',DocumentStatusUpdated' => 'handleStatusUpdate',
    //     'echo-private:document.'.$document->id.',DocumentSignatureUpdated' => 'handleSignatureUpdate',
    //     'refreshNotifications' => 'refresh'
    // ];

    public function mount(Document $document)
    {
        $this->document = $document;
        $this->loadNotifications();
    }
    public function getListeners()
    {
        return [
            'echo-private:document.'.$this->document->id.',DocumentStatusUpdated' => 'handleStatusUpdate',
            'echo-private:document.'.$this->document->id.',DocumentSignatureUpdated' => 'handleSignatureUpdate',
            'refreshNotifications' => 'refresh'
        ];
    }
    public function loadNotifications()
    {
        $this->notifications = $this->document->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        $this->unreadCount = $this->document->notifications()
            ->whereNull('read_at')
            ->where('notifiable_id', auth()->id())
            ->count();
    }

    public function handleStatusUpdate($data)
    {
        $this->loadNotifications();
        $this->emit('refreshDocument');
    }

    public function handleSignatureUpdate($data)
    {
        $this->loadNotifications();
        $this->emit('refreshDocument');
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()
            ->where('data->document_id', $this->document->id)
            ->update(['read_at' => now()]);
            
        $this->loadNotifications();
    }

    public function refresh()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.document.document-notifications',  ['unreadCount' => $this->count,'document' => $this->document]);
    }
}