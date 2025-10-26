<div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-bottom: 100px">
                <div class="card-header text-center">
                    <h4 class="mb-0">We value your feedback</h4>
                </div>
                <div class="card-body">

                    @if (session()->has('success'))
                    <section class="d-flex align-items-center justify-content-center vh-100">
                        <div class="text-center p-3 bg-white rounded-4 shadow-lg" style="max-width: 600px;">
                            <div class="mb-4">
                                <i class="fa-solid fa-thumbs-up text-primary" style="font-size: 4rem;"></i>
                            </div>
                            <h1 class="fw-bold text-primary mb-3">Thank You for Your Feedback!</h1>
                            <p class="text-secondary fs-5 mb-4">
                                {{ __('Your response has been recorded successfully. We truly appreciate the time you
                                took to share your thoughts with us.') }}
                            </p>

                            <hr class="my-4">

                            <p class="mb-4 text-muted">
                                Your feedback helps us improve our products and serve you better.
                            </p>

                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="bi bi-house-door-fill me-2"></i> Back to Home
                            </a>
                        </div>
                    </section>
                    @elseif(count($products) > 0)
                    @php
                    $product = $products[$currentStep];
                    $entry = $feedbackEntries[$product->id] ?? [];
                    @endphp

                    <form wire:submit.prevent="submitCurrent">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <img src="{{ asset($product->image ?? NO_IMAGE) }}" alt="img" class="img-thumbnail"
                                    style="height: 100px;">
                                <h5 class="mb-3 text-primary">{{ $product->name }}</h5>

                                <!-- Feedback Category / Subject -->
                                <div class="mb-3">
                                    <label class="form-label">Subject</label>
                                    <select class="form-select"
                                        wire:model="feedbackEntries.{{ $product->id }}.feedback_category">
                                        <option value="">-- Select Subject --</option>
                                        @foreach ($feedbackCategories as $category)
                                        <option value="{{ $category['name'] }}">{{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error("feedbackEntries.{$product->id}.feedback_category")
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Rating (Emoji) -->
                                <div class="rating-group d-flex justify-content-center gap-3 mb-3">
                                    @foreach ([1 => '😞', 2 => '😐', 3 => '🙂', 4 => '😃', 5 => '😍'] as $value =>
                                    $emoji)
                                    @php $inputId = 'rate_' . $product->id . '_' . $value; @endphp
                                    <div class="rating-item text-center">
                                        <input type="radio" id="{{ $inputId }}"
                                            wire:model="feedbackEntries.{{ $product->id }}.rating" value="{{ $value }}"
                                            class="d-none">
                                        <label for="{{ $inputId }}" style="font-size: 30px; cursor: pointer;">
                                            {{ $emoji }}
                                        </label>
                                        <div class="rating-caption" style="font-size: 12px;">
                                            {{ ['Poor', 'Fair', 'Good', 'Great', 'Excellent'][$value - 1] }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error("feedbackEntries.{$product->id}.rating")
                                <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <!-- Comment -->
                                <div class="mb-3">
                                    <textarea class="form-control"
                                        wire:model.defer="feedbackEntries.{{ $product->id }}.comment" rows="3"
                                        placeholder="Share your thoughts (optional)..."></textarea>
                                </div>
                                {{--
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif --}}
                                @if (session()->has('error'))
                                <div class="alert alert-danger" x-data="{ show: true }"
                                    x-init="setTimeout(() => show = false, 5000)" x-show="show">
                                    {{ session('error') }}
                                </div>
                                @endif
                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between">
                                    <!-- Previous -->
                                    <button type="button" class="btn btn-outline-secondary" wire:click="previousStep"
                                        @if ($currentStep===0) disabled @endif>
                                        Previous
                                    </button>

                                    <div class="d-flex gap-2">
                                        <!-- Skip (only show if not last) -->
                                        @if ($currentStep < count($products) - 1) <button type="button"
                                            class="btn btn-warning" wire:click="skipCurrent">
                                            Skip
                                            </button>
                                            @endif

                                            <!-- Next or Submit -->
                                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                                {{-- When not loading OR not on final step --}}
                                                <span wire:loading.remove>
                                                    {{ $currentStep === count($products) - 1 ? __('Finish') : __('Next')
                                                    }}
                                                </span>
                                                {{-- Show spinner only when loading AND on final step --}}
                                                @if ($currentStep === count($products) - 1)
                                                <span wire:loading wire:target="submitCurrent">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    {{ __('Submitting please wait...') }}
                                                </span>
                                                @endif
                                            </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-info">No products found for feedback.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>