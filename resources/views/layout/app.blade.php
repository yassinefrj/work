<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var currentUser = @json(auth()->user());
    </script>
    <script src="{{ asset('js/Task.js') }}"></script>
    <script src="{{ asset('js/task_details.js') }}"></script>
    <script src="{{ asset('js/group.js') }}"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="{{ asset('js/groupParticipation.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />


    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('img/Logo_Hand.svg') }}"
                    alt="Mon Logo"></a>
            <a class="navbar-brand" href="{{ route('calendar') }}">Calendar</a>
            @auth
            @if(Auth::user()->isAdmin())
            <div class="dropdown tasks-dropdown">
                <a class="navbar-brand" href="#" role="button">
                    Admin
                </a>
                <div class="dropdown-menu" aria-labelledby="tasksDropdownLink">
                    <a class="navbar-brand dropdown-item" href="{{ route('verify') }}">Verify users</a>
                    <a class="navbar-brand dropdown-item" href="{{ route('report') }}">Report</a>
                </div>
            </div>
            @endif
            @endauth

            <div class="dropdown tasks-dropdown">
                <a class="navbar-brand" href="#" role="button">
                    Task
                </a>
                <div class="dropdown-menu" aria-labelledby="tasksDropdownLink">
                    <a class="navbar-brand dropdown-item" href="{{ route('tasks.index') }}">Tasks list</a>
                    <a class="navbar-brand dropdown-item" href="{{ route('tasks.create') }}">Create task</a>
                </div>
            </div>

            <div class="dropdown groups-dropdown">
                <a class="navbar-brand" href="#" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Group
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <span id="groupNotificationBadge" class="badge badge-danger"></span>
                    @endif
                </a>
                <div class="dropdown-menu" aria-labelledby="groupsDropdownLink">
                    <a class="navbar-brand dropdown-item" href="{{ route('groups.index') }}">Group list</a>
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <a class="navbar-brand dropdown-item" href="{{ route('groups.add_group') }}">Create Group</a>
                    @endif
                    <a class="navbar-brand dropdown-item" href="{{ route('groups.participants') }}">Group Participants
                        @if(auth()->check() && auth()->user()->isAdmin())
                        <span id="groupParticipationNotificationBadge" class="badge badge-danger"></span>
                        @endif
                    </a>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('.dropdown').hover(function() {
                        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(300);
                    }, function() {
                        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
                    });
                });
            </script>
        </div>

        @auth
            <!-- Settings Dropdown -->
            <div class="sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-black bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-black bg-white">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link class="text-black bg-white" :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        @else
            <!-- Settings Dropdown -->
            <div class="sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-black bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700">
                            <div>Login</div>

                            <div class="ml-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('register')" class="text-black bg-white">
                            {{ __('Register') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('login')" class="text-black bg-white">
                            {{ __('Login') }}
                        </x-dropdown-link>

                    </x-slot>
                </x-dropdown>
            </div>
        @endauth


    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
    @stack('scripts')
</body>
<footer class="text-center text-muted">
    <p>&copy; 2023 WorkTogether. All rights reserved.</p>
</footer>
<script>
    $(document).ready(function() {
        updateNotificationBadges();
    });

</script>


</html>
