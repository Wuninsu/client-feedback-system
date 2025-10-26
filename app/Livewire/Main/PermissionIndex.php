<?php

namespace App\Livewire\Main;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class PermissionIndex extends Component
{
    use WithPagination;
    public $search = ''; // Search term
    public $permission_id;
    public $confirmingDelete = false; // To show a confirmation dialog
    public $isEdit = false; // To show a confirmation dialog
    public $name;
    public $description;
    public $sanitizedSearch;

    protected function rules()
    {
        return [
            'name' => 'required|min:4|max:255|unique:permissions,name,' . $this->permission_id,
        ];
    }

    public function savePermission()
    {
        $validatedData = $this->validate(); // validate user inputs 
        $save = Permission::create($validatedData);
        if (!$save) {
            sweetalert()->error('Fail to create permission. Please try again.');
            return;
        }
        sweetalert()->success('Permission created successfully.'); // send success msg
        $this->reset(); // reset form fields

    }


    public function editPermission(Permission $permission)
    {
        $this->permission_id = $permission->id;
        $this->name = $permission->name;
        $this->description = $permission->description;
        $this->isEdit = true;
    }

    public function updatePermission()
    {
        $validatedData = $this->validate();
        $permission = Permission::find($this->permission_id);
        if ($permission) {
            $permission->update($validatedData);
            $this->reset(); // Clear the form fields
            sweetalert()->success('Permission updated successfully.');
        } else {
            sweetalert()->error('Permission not found.');
        }
    }


    public function confirmDelete($id)
    {
        $this->dispatch('confirm', id: $id);
    }

    #[On('delete')]
    public function handleDelete($id)
    {
        $permission = Permission::find($id);

        if ($permission) {
            $permission->delete();
            sweetalert()->success('The permission has been deleted successfully..');
        } else {
            sweetalert()->error('Permission not found.');
        }

        $this->reset();
    }


    #[Title('Permissions')]
    public function render()
    {
        // Fetch permissions based on the search term or paginate all permissions
        $this->sanitizedSearch = str_replace(["'", '"'], '', $this->search);
        $permissions = Permission::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(7);
        return view('livewire.main.permission-index', ['permissions' => $permissions]);
    }
    
}
