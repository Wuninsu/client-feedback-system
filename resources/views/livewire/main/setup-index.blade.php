<div>
    <form wire:submit.prevent="updateData">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach ($settings as $key => $value)
                        <div class="col-md-6 form-group mb-2">
                            <label for="value_{{ $key }}" class="mb-0">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                            @if (in_array($key, ['site_description', 'keywords', 'about_us', 'about_us_sub', 'mission_statement', 'vision_statement', 'why_choose_us']))
                                <textarea id="value_{{ $key }}" rows="3" wire:model="settings.{{ $key }}"
                                    class="form-control @error('settings.' . $key) border-danger is-invalid @enderror">{{ old('settings.' . $key, $value) }}</textarea>
                            @elseif (in_array($key, ['logo', 'favicon', 'main_background_image', 'footer_background_image']))
                                <!-- Logo/Favicon Upload -->
                                <input type="file" id="value_{{ $key }}" wire:model="settings.{{ $key }}"
                                    class="form-control @error('settings.' . $key) border-danger is-invalid @enderror">
                                @if ($value)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . ($value ?? NO_IMAGE)) }}" alt="{{ $key }}" width="100">
                                    </div>
                                @endif

                            @elseif ($key == 'why_choose_us_points')
                                <!-- Custom UI for Why Choose Us JSON -->
                                @php
                                    $points = $settings[$key] ?? []; // now it's already decoded in Livewire
                                @endphp

                                @foreach ($points as $i => $point)
                                    <div class="border p-2 mb-2">
                                        <input type="text" wire:model="settings.{{ $key }}.{{ $i }}.title" class="form-control mb-1"
                                            placeholder="Title {{ $i + 1 }}">

                                        <textarea wire:model="settings.{{ $key }}.{{ $i }}.description" class="form-control"
                                            placeholder="Description {{ $i + 1 }}"></textarea>
                                    </div>
                                @endforeach

                                {{-- <button type="button" wire:click="addWhyChooseUsPoint"
                                    class="btn btn-sm btn-outline-primary">Add Point</button> --}}
                            @else
                                <!-- Regular Text Input -->
                                <input type="text" id="value_{{ $key }}" wire:model="settings.{{ $key }}"
                                    class="form-control @error('settings.' . $key) border-danger is-invalid @enderror">
                            @endif

                            @error('settings.' . $key)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary float-end">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>