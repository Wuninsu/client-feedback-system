<?php

namespace App\Livewire\Main;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{

    use WithPagination;
    public $search = '';
    public $role = '';
    public $user_uuid, $roles;
    public $showDelete = false;


    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
    ];

    public function mount()
    {
        $roles = Role::whereNotIn('name', ['dev'])->get();
        $this->roles = $roles;
    }


    public function confirmDelete($uuid)
    {
        $this->dispatch('confirm', uuid: $uuid);
    }

    #[On('delete')]
    public function handleDelete($uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        if ($user) {
            // if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            //     Storage::disk('public')->delete($user->avatar);
            // }
            if ($user->avatar) {
                // Normalize the path (strip /storage/ if present)
                $relativePath = str_replace('/storage/', '', $user->avatar);

                // 1. Delete from 'public' disk (storage/app/public)
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }

                // 2. Delete from public directory directly (public/storage/...)
                $publicPath = public_path('storage/' . $relativePath);
                if (file_exists($publicPath)) {
                    @unlink($publicPath); // Suppress error if not found
                }
            }

            $user->delete();
            sweetalert()->success('User deleted successfully.');
        } else {
            sweetalert()->error('User not found.');
        }
    }



    #[Title('Users')]
    public function render()
    {
        // Cache::put("user_phone", auth('web')->user()->phone, now()->addMinutes(2));

        $users = User::query()
            ->when(!auth('web')->user()->hasAnyRole(['dev']), function ($query) {
                $query->whereDoesntHave('roles', function ($query) {
                    $query->whereIn('name', ['dev']);
                });
            })
            ->when($this->role, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->role);
                });
            })
            ->when($this->search, function ($query) {
                $query->where('username', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(paginationLimit());

        return view('livewire.main.user-index', compact('users'));
    }
}
