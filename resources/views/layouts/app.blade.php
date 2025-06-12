<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex" id="wrapper">
        <div class="bg-white border-end" id="sidebar-wrapper" style="width: 280px;">
            <div class="sidebar-heading border-bottom bg-light p-3">
                <a href="{{ route('dashboard') }}" class="text-dark text-decoration-none h5">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <div class="list-group list-group-flush">
                {{-- Navigasi akan ditempatkan di sini --}}
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="{{ route('dashboard') }}">Dashboard</a>
                
                {{-- Contoh Navigasi Berdasarkan Peran --}}
                @if(auth()->user()->role->slug == 'admin')
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#">Manajemen Pengguna</a>
                @endif
                 @if(auth()->user()->role->slug == 'pm')
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#">Proyek Saya</a>
                @endif
                 @if(auth()->user()->role->slug == 'employee')
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#">Tugas Saya</a>
                @endif

            </div>
        </div>
        <div id="page-content-wrapper" class="w-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Log Out</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="container-fluid p-4">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts  {{-- Tambahkan ini --}}
    @stack('scripts')
</body>
</html>