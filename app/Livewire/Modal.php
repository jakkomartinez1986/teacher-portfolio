<?php

namespace App\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public $component;
    public $arguments = [];
    public $maxWidth;

    protected $listeners = [
        'open-modal' => 'open',
    ];

    public function open($params)
    {
        $this->component = $params['component'];
        $this->arguments = $params['arguments'] ?? [];
        $this->maxWidth = $params['maxWidth'] ?? '2xl';
        
        $this->dispatch('open-modal-event');
    }

    public function render()
    {
        return view('livewire.modal');
    }
   
}
