<div>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-shield-lock-fill me-2"></i> Manage Permissions for <span class="fw-semibold"><i>({{
                        $user->name }})</i></span>
            </h4>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="updateUserPermissions">
                <div class="mb-4">
                    <label class="form-label fw-bold">All Permissions</label>
                    <div class="row">
                        @foreach ($allPermissions->chunk(ceil($allPermissions->count() / 3)) as $chunk)
                        <div class="col-md-4">
                            <ul class="list-group list-group-flush">
                                @foreach ($chunk as $permission)
                                <li class="list-group-item px-0 py-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="perm-{{ $permission->id }}"
                                            value="{{ $permission->name }}" wire:model="permissions">
                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="alert alert-info" role="alert">
                    <strong>Note:</strong> This form allows you to <strong>update only direct user-specific
                        permissions</strong>.
                    Permissions inherited from roles will remain unchanged. To manage role-based permissions, please
                    update the user's
                    roles separately.
                </div>

                <div class="alert alert-warning" role="alert">
                    <strong>Revoke Notice:</strong> Clicking the <strong>“Revoke All Permissions”</strong> button will
                    remove <u>all
                        user-specific permissions</u>.
                    This does <strong>not affect permissions granted through roles</strong>.
                </div>


                <div class="text-end">
                    <button type="button" class="btn btn-secondary" wire:click="revokeAllUserBasedPermissions">Revoke
                        User
                        Permissions</button>
                    <button type="submit" class="btn btn-primary">Update Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>