<?php

namespace App\Livewire\Main;

use App\Models\MessageTemplate;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class TemplateIndex extends Component
{
    use WithPagination;
    public $type = 'all';

    public function confirmDelete($id)
    {
        $this->dispatch('confirm', id: $id);
    }

    #[On('delete')]
    public function handleDelete($id)
    {
        $template = MessageTemplate::find($id);
        if ($template) {
            $template->delete();
            sweetalert()->success('Template deleted successfully.');
        } else {
            sweetalert()->error('Template not found.');
        }
    }

    #[Title('Templates')]
    public function render()
    {
        $templates = MessageTemplate::when($this->type !== 'all', function ($query) {
            $query->where('type', $this->type);
        })->latest()
            ->paginate(paginationLimit());

        return view('livewire.main.template-index', [
            'templates' => $templates,
        ]);
    }
}
