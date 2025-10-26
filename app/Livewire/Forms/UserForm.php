<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    use WithFileUploads;
    public $user_id;
    public $roles;

    public $username, $email, $name, $phone, $avatar, $status, $role, $password, $address;
    public $password_confirmation;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|min:4|max:255|alpha_dash|unique:users,username,' . $this->user_id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user_id,
            'phone' => 'required|regex:/^\d{10,13}$/|unique:users,phone,' . $this->user_id,
            'role' => 'required|array',
            'role.*' => 'exists:roles,name',
            'status' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => $this->user_id ? 'nullable|confirmed|min:6' : 'required|confirmed|min:6',
        ];
    }

    public $showImg;

    public function mount(User $user)
    {
        $this->roles = Role::whereNotIn('name', ['dev'])->get();


        if ($user->uuid) {
            $uid = $user->uuid;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->username = $user->username;
            $this->status = $user->status == 1 ? true : false;
            $this->showImg = $user->avatar;
            $this->address = $user->address;
            $this->phone = $user->phone;
            $this->role = $user->getRoleNames();

            $this->user_id = $user->id;
        }
    }



    public function saveUser()
    {

        $validated = $this->validate();

        $user = User::find($this->user_id);
        $filePath = $user ? $user->avatar : null;

        // Handle file upload if a new image is selected
        if ($this->avatar) {
            if ($filePath) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            $filePath = uploadFile($this->avatar, 'avatars');
        }

        // Check if updating or creating
        if ($this->user_id) {
            // Update User
            $user = User::findOrFail($this->user_id);
            $user->update([
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'name' => $this->name,
                'avatar' => $filePath,
                'address' => $this->address,
                'status' => $this->status,
            ]);

            // Update Password if Provided
            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }
        } else {
            // Create New User
            $user = User::create([
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'name' => $this->name,
                'avatar' => $filePath,
                'address' => $this->address,
                'status' => $this->status,
                'password' => Hash::make($this->password), // Required on creation
            ]);
        }

        // ===============================
        // Role assignment restrictions
        // ===============================

        $roleToAssign = $validated['role'];
        $currentUser = auth('web')->user();

        // Log the current user's role and the roles they are attempting to assign
        Log::debug('Role Assignment Attempt:', [
            'current_user_role' => $currentUser->getRoleNames()->first(),
            'role_to_assign' => $roleToAssign
        ]);

        // Define assignable roles per user role
        $assignableRoles = match ($currentUser->getRoleNames()->first()) {
            'dev' => ['dev', 'admin', 'client', 'staff'],
            'admin' => ['client', 'staff'],
            default => ['client'],
        };

        // Log the assignable roles for the current user
        Log::debug('Assignable Roles:', ['assignable_roles' => $assignableRoles]);

        // Check each role in the array to see if it is assignable
        $invalidRoles = array_diff($roleToAssign, $assignableRoles);

        if (!empty($invalidRoles)) {
            // Log the invalid roles that are being denied
            Log::warning('Invalid Role Assignment Attempt:', [
                'invalid_roles' => $invalidRoles
            ]);

            flash('You are not allowed to assign these roles: ' . implode(', ', $invalidRoles), 'error', [], 'Permission Denied');
            return;
        }

        // Optionally log the successful role assignment process
        Log::info('Role Assignment Successful:', [
            'role_to_assign' => $roleToAssign
        ]);

        // Proceed with the role assignment process (e.g., assigning the role)

        $user->syncRoles($roleToAssign);

        sweetalert()->success($this->user_id ? 'User updated successfully' : 'User created successfully');

        $this->reset(); // Reset form
        return redirect()->route('users.index');
    }

    #[Title('Manage Users')]
    public function render()
    {
        return view('livewire.forms.user-form');
    }
}
