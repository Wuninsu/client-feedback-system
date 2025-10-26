<?php

namespace App\Livewire\Main;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    use WithPagination;
    public $search = ''; // Search term
    public $role_id;
    public $confirmingDelete = false; // To show a confirmation dialog
    public $isEdit = false; // To show a confirmation dialog
    public $name;

    protected function rules()
    {
        return [
            'name' => 'required|min:4|max:255|unique:roles,name,' . $this->role_id,
        ];
    }

    public function saveRole()
    {
        $validatedData = $this->validate(); // validate user inputs 
        $save = Role::create($validatedData);
        if (!$save) {
            sweetalert()->error('Fail to create Role. Please try again.');
            return;
        }
        sweetalert()->success('Role created successfully.'); // send success msg
        $this->reset(); // reset form fields

    }


    public function editRole(Role $role)
    {
        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->isEdit = true;
    }

    public function updateRole()
    {
        $validatedData = $this->validate();
        $role = Role::find($this->role_id);
        if ($role) {
            // $role->update($validatedData);
            $this->reset(); // Clear the form fields
            sweetalert()->success('Role updated successfully.');
        } else {
            sweetalert()->error('Role not found.');
        }
    }



    public function confirmDelete($id)
    {

        $this->dispatch('confirm', id: $id);
    }

    #[On('delete')]
    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            sweetalert()->success('The role has been deleted successfully...');
        } else {
            sweetalert()->error('Role not found.');
        }
        $this->reset();
    }

    #[Title('Roles')]
    public function render()
    {
        // Fetch roles based on the search term or paginate all roles
        $roles = Role::whereNot('name', 'super-admin')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(4);
        return view('livewire.main.role-index', ['roles' => $roles]);
    }

}
