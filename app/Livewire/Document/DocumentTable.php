<?php

namespace App\Livewire\Document;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Document\Document;
use Illuminate\Support\Facades\Auth;

class DocumentTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $type = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'type' => ['except' => ''],
        'sortField',
        'sortDirection',
        'perPage'
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // public function render()
    // {
    //     $query = Document::query()
    //         ->with(['type', 'creator', 'year'])
    //         ->when($this->search, function ($query) {
    //             $query->where('title', 'like', '%'.$this->search.'%')
    //                   ->orWhere('description', 'like', '%'.$this->search.'%');
    //         })
    //         ->when($this->status, function ($query) {
    //             $query->where('status', $this->status);
    //         })
    //         ->when($this->type, function ($query) {
    //             $query->where('type_id', $this->type);
    //         });

    //     // Filtros adicionales para usuarios no administradores
    //     if (!Auth::user()->hasRole('admin')) {
    //         $query->where(function($q) {
    //             $q->where('creator_id', Auth::id())
    //               ->orWhereHas('authors', function($q) {
    //                   $q->where('user_id', Auth::id());
    //               })
    //               ->orWhereHas('signatures', function($q) {
    //                   $q->where('user_id', Auth::id())
    //                     ->orWhere(function($q) {
    //                         $q->whereNull('user_id')
    //                           ->whereIn('role_id', Auth::user()->roles->pluck('id'));
    //                     });
    //               });
    //         });
    //     }

    //     $documents = $query->orderBy($this->sortField, $this->sortDirection)
    //                        ->paginate($this->perPage);

    //     return view('livewire.document.document-table', [
    //         'documents' => $documents,
    //         'statuses' => [
    //             'DRAFT' => 'Borrador',
    //             'PENDING_REVIEW' => 'Pendiente de Revisión',
    //             'APPROVED' => 'Aprobado',
    //             'REJECTED' => 'Rechazado',
    //             'ARCHIVED' => 'Archivado'
    //         ],
    //         'types' => \App\Models\Settings\Document\DocumentType::pluck('name', 'id')
    //     ]);
    // }
    public function render()
{
    $statuses = [
        'DRAFT' => 'Borrador',
        'PENDING_REVIEW' => 'Pendiente de Revisión',
        'APPROVED' => 'Aprobado',
        'REJECTED' => 'Rechazado',
        'ARCHIVED' => 'Archivado'
    ];

    $query = Document::query()
        ->with(['type', 'creator', 'year'])
        ->when($this->search, function ($query) {
            $query->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
        })
        ->when($this->status, function ($query) {
            $query->where('status', $this->status);
        })
        ->when($this->type, function ($query) {
            $query->where('type_id', $this->type);
        });

    // Filtros adicionales para usuarios no administradores
    if (!Auth::user()->hasRole('admin')) {
        $query->where(function($q) {
            $q->where('creator_id', Auth::id())
              ->orWhereHas('authors', function($q) {
                  $q->where('user_id', Auth::id());
              })
              ->orWhereHas('signatures', function($q) {
                  $q->where('user_id', Auth::id())
                    ->orWhere(function($q) {
                        $q->whereNull('user_id')
                          ->whereIn('role_id', Auth::user()->roles->pluck('id'));
                    });
              });
        });
    }

    $documents = $query->orderBy($this->sortField, $this->sortDirection)
                       ->paginate($this->perPage);

    return view('livewire.document.document-table', [
        'documents' => $documents,
        'statuses' => $statuses,
        'types' => \App\Models\Settings\Document\DocumentType::pluck('name', 'id')
    ]);
}
}