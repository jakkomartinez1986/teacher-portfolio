<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataTable extends Component
{
    use WithPagination;
    public $model;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $perPageOptions = [5, 10, 25, 50];

    public $showRoute;
    public $editRoute;
    public $newRoute = null;
    public $view;
    public $useModal = false;
    public $confirming;
    public $modelactive;
    protected $listeners = ['deleteConfirmed','toggleActivation'];


    // public function render()
    // {
    //     return view('livewire.data-table');
    // }
    public function mount($model, $showRoute, $editRoute, $view, $newRoute = null, $useModal = false)
    {
        $this->model = $model;
        $this->showRoute = $showRoute;
        $this->editRoute = $editRoute;
        $this->newRoute = $newRoute;
        $this->useModal = $useModal;
        $this->view = $view;
        $parts = explode("\\", $this->model);
        $this->modelactive = strtolower(end($parts));
    }

    public function updatingSearch() { $this->resetPage(); }

    public function updatingPerPage() { $this->resetPage(); }

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field)
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';

        $this->sortField = $field;
    }

    // public function openNewModal()
    // {
    //     if ($this->useModal) {
    //         $modelName = $this->getModelName();
    //         $this->dispatch('openModalFor', $modelName);
    //     } else {
    //         return redirect()->route($this->newRoute);
    //     }
    // }

    public function toggleActivation($id)
    {
        $item = $this->model::findOrFail($id);
        $item->status = $item->status === 1 ? 0 : 1;
        $item->save();
    }

    public function delete($id)
    {
        $this->confirming = $id;
        $modelName = $this->getModelName();

        $this->dispatch('delete-prompt', [
            'icon' => 'warning',
            'title' => "¿Estás seguro de eliminar el ID: $id del modelo $modelName?",
            'text' => '¡No podrás revertir esto!',
            'id' => $id
        ]);
    }

    public function deleteConfirmed($id)
    {
        $item = $this->model::find($id);
        $modelName = $this->getModelName();

        if (!$item) {
            $this->dispatch('swal', [
                'title' => 'Error!',
                'text' => "Modelo $modelName con ID $id no encontrado.",
                'icon' => 'error'
            ]);
            return;
        }
        // Verificar si el usuario tiene permiso para eliminar este ítem
        if (!auth()->user()->can('borrar-'. $this->modelactive)) {
            $this->dispatch('swal', [
                'title' => 'No Autorizado!',
                'text' => "No tienes permiso para eliminar este modelo.",
                'icon' => 'error'
            ]);
            return;
        }

        // Si hay un archivo asociado al modelo, eliminarlo (si es necesario)
        if ($modelName === 'file' && $item->file_path) {
            Storage::delete($item->file_path);
        }
        // if ($modelName === 'file' && $item->file_path) {
        //     Storage::delete($item->file_path);
        // }

         $item->delete();

        $this->dispatch('swal', [
            'title' => '¡Eliminado!',
            'text' => "Modelo $modelName con ID $id eliminado correctamente.",
            'icon' => 'success'
        ]);
    }

    public function export()
    {
        // Lógica para exportar si se implementa después
        $this->dispatch('notify', 'Exportar registros');
    }

    public function render()
    {
        $query = $this->model::search($this->search);

        if ($this->getModelName() === 'file') {
            $query->where('user_id', Auth::id());
        }
        if ($this->getModelName() === 'user') {
            $data = $query
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'ESTUDIANTE');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        }else {
            $data = $query->orderBy($this->sortField, $this->sortDirection)
                      ->paginate($this->perPage);
        }

       

        return view('livewire.data-table', ['data' => $data]);
    }

    private function getModelName()
    {
        return strtolower(class_basename($this->model));
    }
}