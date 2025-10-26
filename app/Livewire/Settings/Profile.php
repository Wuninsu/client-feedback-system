<?php

namespace App\Livewire\Settings;

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;
    public string $name = '';

    public string $email = '';
    public $avatar;
    public $phone, $address;
    public $username;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';
    public $activeTab, $showImg, $permissions;
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Mount the component.
     */
    #[Title('Settings')]
    public function mount(): void
    {
        $this->activeTab = 'info';

        $user = auth('web')->user();
        $this->address = $user->address;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->showImg = $user->avatar;

        $this->permissions = $user->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => 'required|min:4|max:255|alpha_dash|unique:users,username,' . auth('web')->id(),
            'phone' => 'required|regex:/^\d{10,13}$/|unique:users,phone,' . auth('web')->id(),
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'address' => 'nullable|string',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $filePath = $user ? $user->avatar : null;

        // Handle file upload if a new image is selected
        if ($this->avatar) {
            if ($filePath) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            $user->avatar = uploadFile($this->avatar, 'avatars');
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', PasswordRule::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}
