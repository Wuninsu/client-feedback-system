<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ManagePermissions extends Component
{
    public $role;
    public $permissions;
    public $selectedPermissions = [];
    public $selectAll = false;
    public $permissionsGrouped;
    public $searchTerm = '';

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->loadPermissions(); // Load and group permissions
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray(); // Pre-fill assigned permissions
    }

    public function updatedSearchTerm()
    {
        $this->loadPermissions(); // Reload permissions whenever the search term changes
    }
    // public function mount(Role $role)
    // {
    //     $this->role = $role;
    //     $this->permissions = collect(Permission::all());
    //      // Group permissions by prefix
    //      $this->permissionsGrouped = collect(Permission::all())->groupBy(function ($permission) {
    //         return explode('.', $permission->name)[0]; // Group by the prefix before "."
    //     });
    //     $this->selectedPermissions = $role->permissions->pluck('id')->toArray(); // Pre-fill assigned permissions
    // }

    public function toggleSelectAll($selectAll)
    {
        $this->selectedPermissions = $selectAll ? $this->permissions->pluck('id')->toArray() : [];
        $this->role->permissions()->sync($this->selectedPermissions);
    }

    // Handle the "Select All" checkbox change
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPermissions = $this->permissions->pluck('id')->toArray(); // Select all permissions
            sweetalert()->success('All permissions assigned successfully.');
        } else {
            $this->selectedPermissions = []; // Deselect all permissions
            sweetalert()->success('All permissions revoked successfully.');
        }

        // Sync the selected permissions to the role
        $this->role->permissions()->sync($this->selectedPermissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }



    public function updatePermissions()
    {
        $this->role->permissions()->sync($this->selectedPermissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        sweetalert()->success('Permissions updated successfully.');
    }


    protected function loadPermissions()
    {
        $query = Permission::query();

        // Apply search filter
        if (!empty($this->searchTerm)) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        $this->permissions = collect($query->get());
        $this->permissionsGrouped = $this->permissions->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });
    }
    #[Title('Manage Permissions')]
    public function render()
    {
        return view('livewire.forms.manage-permissions', [
            'permissionsGrouped' => $this->permissionsGrouped,
        ]);
    }
}
