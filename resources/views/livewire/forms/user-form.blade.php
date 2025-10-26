<div>
    <form wire:submit.prevent="saveUser">
        <div class="row">
            <div class="col-lg-8 col-12">
                <!-- card -->
                <div class="card mb-4">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Username</label>
                                <input type="text" wire:model="username"
                                    class="form-control @error('username') border-danger is-invalid @enderror"
                                    placeholder="Enter user name">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') border-danger is-invalid @enderror"
                                    placeholder="Enter full name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Email</label>
                                <input type="email" wire:model="email"
                                    class="form-control  @error('email') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Phone</label>
                                <input type="text" wire:model="phone"
                                    class="form-control  @error('phone') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- @if (!isset($user_id)) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Password</label>
                                <input type="text" wire:model.defer="password"
                                    class="form-control  @error('password') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-0">Confirm Password</label>
                                <input type="text" wire:model.defer="password_confirmation"
                                    class="form-control  @error('password_confirmation') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- @endif --}}

                            <!-- Address -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid border-danger @enderror"
                                    rows="2" wire:model='address'
                                    placeholder="e.g. 123 Main St, Accra, Ghana"></textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <!-- card -->
                <div class="card mb-4">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="form-check form-switch mb-4">
                            <input {{ $status ? 'checked' : '' }}
                                class="form-check-input  @error('status') border-danger is-invalid @enderror"
                                wire:model="status" type="checkbox" role="switch" id="flexSwitchStock">
                            <label class="form-check-label" for="flexSwitchStock">Status</label>
                        </div>
                        <div class="">
                            <div class="mb-3">
                                <label for="roles">Role</label>
                                <select class="form-select @error('role') border-danger is-invalid @enderror" id="roles"
                                    wire:model="role" multiple>
                                    <option value="" selected>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- input -->
                            <div class="mb-3">
                                <label class="form-label mb-0">Avatar</label>
                                <input type="file" wire:model="avatar"
                                    class="form-control  @error('avatar') border-danger is-invalid @enderror"
                                    placeholder="">
                                @error('avatar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <span wire:loading wire:target="avatar" class="text-success">uploading...</span>
                            <div class="">
                                <img width="100" src="{{ $avatar ? $avatar->temporaryUrl() : $showImg ?? NO_IMAGE }}"
                                    alt="" srcset="">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <!-- button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{__($user_id ? 'Update' : 'Create') }} User</span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            {{__($user_id ? 'Updating' : 'Creating') }} User
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>