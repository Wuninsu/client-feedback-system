<?php

namespace App\Livewire\Main;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class ManageUserPermission extends Component
{
    public $user;

    public $permissions = [];
    public $allPermissions;
    public function mount(User $user)
    {
        $this->allPermissions = Permission::all();
        // Pre-check assigned permissions
        $this->permissions = $user->getAllPermissions()->pluck('name')->toArray();
    }
   
    public function updateUserPermissions()
    {
        $this->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Get current permissions the user has
        $currentPermissions = $this->user->getAllPermissions()->pluck('name')->toArray();

        // Filter the permissions that the user doesn't have
        $permissionsToAssign = array_diff($this->permissions, $currentPermissions);

        if (count($permissionsToAssign) > 0) {
            // Assign only the missing permissions
            $this->user->givePermissionTo($permissionsToAssign);
        }

        // Refresh the user model
        $this->user->refresh();

        // Recalculate permission lists
        $this->permissions = $this->user->getAllPermissions()->pluck('name')->toArray();

        flash()->success('Permissions updated successfully.');
    }


    public function revokeAllUserBasedPermissions()
    {
        $this->user->syncPermissions([]);
        // Refresh the user model
        $this->user->refresh();
        // Recalculate permission lists
        $this->permissions = $this->user->getAllPermissions()->pluck('name')->toArray();
        flash()->success('All user-based permissions have been revoked.');
    }

    #[Title('Manage User Permissions')]
    public function render()
    {
        return view('livewire.main.manage-user-permission');
    }
}
