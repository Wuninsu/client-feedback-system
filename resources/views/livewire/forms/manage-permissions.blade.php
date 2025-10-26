<div>
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manage Permissions for Role: <strong>{{ $role->name }}</strong></h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <input type="text" wire:model.live.debounce.300ms="searchTerm"
                        placeholder="Search permissions by name..." class="form-control mb-3" />
                </div>
                <form wire:submit.prevent="updatePermissions">
                    <div class="form-group">
                        <label class="form-label"><strong>Available Permissions</strong></label>
                        <div class="row">
                            <!-- Select All Checkbox -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" wire:model.change="selectAll"
                                        id="selectAll">
                                    <label class="form-check-label" for="selectAll">Select All</label>
                                </div>
                            </div>
                            @foreach ($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="permission-{{ $permission['id'] }}" value="{{ $permission['id'] }}"
                                            wire:model="selectedPermissions">
                                        <label class="form-check-label" for="permission-{{ $permission['id'] }}">
                                            {{ $permission['name'] }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            {{-- @foreach ($permissionsGrouped as $groupName => $groupPermissions)
                            <div class="mb-3">
                                <h5>{{ ucfirst($groupName) }}</h5> <!-- Display group name -->
                                <div class="row">
                                    @foreach ($groupPermissions as $permission)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="permission-{{ $permission['id'] }}" value="{{ $permission['id'] }}"
                                                wire:model="selectedPermissions">
                                            <label class="form-check-label" for="permission-{{ $permission['id'] }}">
                                                {{ $permission['description'] }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                            @endforeach --}}

                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Permissions</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>