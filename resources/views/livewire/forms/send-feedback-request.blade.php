<div>
    <style>
        .select2-container {
            width: 100% !important;
            z-index: 9999 !important;
        }

        .select2-container--open {
            width: 100% !important;
            z-index: 9999 !important;
        }
    </style>
    <div wire:ignore.self class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Send Feedback Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form wire:submit="send">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Contact (Phone/Email)</label>
                            <input type="text"
                                class="form-control @error('client_contact') is-invalid border-danger @enderror"
                                wire:model.blur="client_contact">
                            @error('client_contact')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Client Name</label>
                            <input type="text"
                                class="form-control @error('client_name') is-invalid border-danger @enderror"
                                wire:model="client_name">
                            @error('client_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Products</label>
                            <div wire:ignore class="">
                                <select class="form-select" id="productSelect" wire:model='product_ids' multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_ids')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong> </span>
                                @enderror
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Send Request') }}</span>
                            <span wire:loading wire:target="send">
                                <i class="fas fa-spinner fa-spin"></i>
                                {{__('Sending please wait...') }}
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('close-modal', (event) => {
            $('#productSelect').val(null).trigger('change');
            $('#feedbackModal').modal('hide');
        });
    </script>
    @endscript

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const productSelect = $('#productSelect');

            // Initialize Select2
            productSelect.select2();

            // Trigger Livewire event on change
            productSelect.on('change', function (e) {
                @this.set('product_ids', $(this).val());
            });

            // Reinitialize Select2 after Livewire updates
            Livewire.hook('message.processed', (message, component) => {
                productSelect.select2();
            });
        });

    </script>
</div>