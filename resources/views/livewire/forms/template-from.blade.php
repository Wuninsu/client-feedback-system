<div>
    <div class="">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    {{ $templateId ? 'Edit Template' : 'Add Template' }}
                </h5>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="save" class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Template Name</label>
                        <input type="text" class="form-control @error('name') border-danger is-invalid @enderror"
                            wire:model.defer="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select class="form-select @error('type') border-danger is-invalid @enderror"
                            wire:model.change="type">
                            <option value="sms">SMS</option>
                            <option value="email">Email</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    @if($type === 'email')
                        <div class="col-12">
                            <label class="form-label">Email Subject</label>
                            <input type="text" class="form-control @error('subject') border-danger is-invalid @enderror"
                                wire:model.defer="subject">
                            @error('subject')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label">Message Body</label>
                        <textarea rows="6" class="form-control @error('body') border-danger is-invalid @enderror"
                            wire:model.defer="body"></textarea>
                        @error('body')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="activeCheck">
                            <label class="form-check-label" for="activeCheck">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{__($templateId ? 'Update' : 'Create') }} Template</span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                {{__($templateId ? 'Updating' : 'Creating') }} Template
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>