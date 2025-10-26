<div class="container">
    <div class="justify-content-center">
        <div class="row g-1">
            <!-- Left: Profile Image -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div
                            class=" text-center h-100 d-flex flex-column align-items-center justify-content-center border-x bg-light border-light-subtle hover-shadow transition">

                            <div class="mb-3">
                                <img src="{{ $avatar ? $avatar->temporaryUrl() : asset($showImg ?? NO_IMAGE) }}"
                                    alt="Avatar Preview" class="rounded-circle img-thumbnail shadow-sm"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <ul class="list-group w-100 rounded-0">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Role:</span>
                                    <span>{{ auth()->user()->getRoleNames()->first() ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Full Name:</span>
                                    <span>{{ $name }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Email:</span>
                                    <span>{{ $email }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Date Joined:</span>
                                    <span>{{ auth()->user()->created_at->format('M d, Y') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Tabs and Content -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-3" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'info' ? 'active' : '' }}" id="info-tab"
                                    data-bs-toggle="tab" data-bs-target="#info" wire:click="switchTab('info')"
                                    type="button" role="tab">
                                    <i class="bi bi-person"></i>
                                    <span class="d-none d-md-inline"> Info</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'password' ? 'active' : '' }}"
                                    id="password-tab" data-bs-toggle="tab" data-bs-target="#password"
                                    wire:click="switchTab('password')" type="button" role="tab">
                                    <i class="bi bi-shield-lock"></i>
                                    <span class="d-none d-md-inline"> Change Password</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'delete' ? 'active' : '' }} text-danger"
                                    id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete"
                                    wire:click="switchTab('delete')" type="button" role="tab">
                                    <i class="bi bi-trash3"></i>
                                    <span class="d-none d-md-inline"> Delete Account</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'permissions' ? 'active' : '' }}"
                                    id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions"
                                    wire:click="switchTab('permissions')" type="button" role="tab">
                                    <i class="bi bi-lock"></i>
                                    <span class="d-none d-md-inline"> Permissions</span>
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="profileTabContent">
                            <!-- Info Tab -->
                            <div class="tab-pane fade {{ $activeTab == 'info' ? 'show active' : '' }}" id="info"
                                role="tabpanel">
                                <form wire:submit.prevent='updateProfileInformation'>
                                    <div class="row">
                                        <!-- Username -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid border-danger @enderror"
                                                wire:model='username' placeholder="e.g. adamissah123">
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Full Name -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid border-danger @enderror"
                                                wire:model='name' placeholder="e.g. Adam Issah">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid border-danger @enderror"
                                                wire:model='email'>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text"
                                                class="form-control @error('phone') is-invalid border-danger @enderror"
                                                wire:model='phone'>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Address -->
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid border-danger @enderror" rows="2"
                                                wire:model='address' placeholder="e.g. 123 Main St, Accra, Ghana"></textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Avatar -->
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Upload Avatar</label>
                                            <input type="file"
                                                class="form-control @error('avatar') is-invalid border-danger @enderror"
                                                wire:model='avatar' accept="image/*">
                                            @error('avatar')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                {{-- Use d-block
                                                for file inputs --}}
                                            @enderror
                                        </div>


                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Save Changes
                                    </button>
                                </form>


                            </div>

                            <!-- Change Password Tab -->
                            <div class="tab-pane fade {{ $activeTab == 'password' ? 'show active' : '' }}"
                                id="password" role="tabpanel">
                                <form wire:submit.prevent="updatePassword" class="mt-4">
                                    <div class="mb-3">
                                        <label for="current_password"
                                            class="form-label mb-0">{{ __('Current password') }}</label>
                                        <input type="password" wire:model="current_password"
                                            class="form-control  @error('current_password') border-danger is-invalid @enderror"
                                            id="current_password" autocomplete="current-password">
                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password"
                                            class="form-label mb-0">{{ __('New password') }}</label>
                                        <input type="password" wire:model="password" class="form-control"
                                            id="password  @error('password') border-danger is-invalid @enderror"
                                            autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation"
                                            class="form-label mb-0">{{ __('Confirm Password') }}</label>
                                        <input type="password" wire:model="password_confirmation"
                                            class="form-control  @error('password_confirmation') border-danger is-invalid @enderror"
                                            id="password_confirmation" autocomplete="new-password">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="d-flex align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

                                        <x-action-message class="ms-3" on="password-updated">
                                            {{ __('Saved.') }}
                                        </x-action-message>
                                    </div>
                                </form>
                            </div>

                            <!-- Delete Account Tab -->
                            <div class="tab-pane fade {{ $activeTab == 'delete' ? 'show active' : '' }}"
                                id="delete" role="tabpanel">
                                <div class="alert alert-danger">
                                    <strong>Warning!</strong>
                                    {{ __('This action is irreversible. Your account will be
                                                                                                                                                                                                                                                                    permanently deleted. Please enter your password to confirm that you want to permanently delete your account.') }}
                                </div>
                                <form wire:submit="deleteUser">
                                    <div class="mb-3">
                                        <label for="deletePassword" class="form-label">{{ __('Password') }}</label>
                                        <input wire:model="password" type="password" id="deletePassword"
                                            class="form-control @error('password') border-danger is-invalid @enderror">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-danger">Delete My Account</button>
                                </form>
                            </div>

                            <div class="tab-pane fade {{ $activeTab == 'permissions' ? 'show active' : '' }}"
                                id="permissions" role="tabpanel">
                                <h6 class="mb-3">Assigned Permissions</h6>
                                <form>
                                    <div class="row">
                                        @foreach ($permissions as $item)
                                            <div class="col-md-4">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item px-0 py-1">
                                                        {{-- <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="perm-{{ $permission->id }}"
                                                                    value="{{ $permission->name }}"
                                                                    wire:model="permissions">
                                                                <label class="form-check-label"
                                                                    for="perm-{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div> --}}
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="perm1" checked disabled>
                                                            <label class="form-check-label" for="perm1">
                                                                {{ $item }}</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            </div>


                        </div> <!-- tab-content -->
                    </div> <!-- card-body -->
                </div> <!-- col-md-8 -->
            </div> <!-- row -->
        </div> <!-- card -->
    </div>
</div>
