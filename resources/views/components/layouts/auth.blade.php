<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ $title ?? 'Dashboard' }}</title>

    <!-- Bootstrap 5.3 CDN -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Optional: Icon Library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex align-items-center justify-content-center ">
        <div class="w-100" style="max-width: 400px;">
            <a href="{{ route(name: 'home') }}"
                class="d-flex flex-column align-items-center text-decoration-none fw-medium">
                <span class="d-flex align-items-center justify-content-center mb-2"
                    style="width: 100px; height: 100px;">

                    {{-- @php
                    $setups = App\Models\Setup::setupData();
                    @endphp --}}
                    {{-- <img src="{{asset('storage/' . ($setups['favicon'] ?? NO_IMAGE))}}"
                        style="width: 100px; height: 100px;" alt="" srcset=""> --}}
                </span>
                <span class="visually-hidden">{{ config('app.name', 'Laravel') }}</span>
            </a>
            <div class="d-flex flex-column">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>