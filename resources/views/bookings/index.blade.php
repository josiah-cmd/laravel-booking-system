<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BookingApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--bs-body-bg);
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--bs-dark-bg-subtle);
            color: var(--bs-body-color);
            padding-top: 60px;
            transition: all 0.3s ease;
        }
        .sidebar a {
            color: var(--bs-body-color);
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar a:hover {
            background-color: var(--bs-secondary-bg);
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .navbar {
            margin-left: 250px;
            height: 70px;
            background-color: var(--bs-dark-bg-subtle);
            transition: all 0.3s ease;
        }
        .navbar .btn {
            font-size: 0.9rem;
            padding: 6px 12px;
        }
        .notification-item {
            padding: 0.5rem 1rem;
        }
        .card-blue {
            background-color: #3498db;
            color: white;
        }
        .card-green {
            background-color: #2ecc71;
            color: white;
        }
        .table thead {
            background-color: var(--bs-tertiary-bg);
        }
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }
            .sidebar.active {
                left: 0;
            }
            .navbar, .content {
                margin-left: 0;
            }
            .content.shifted, .navbar.shifted {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h5 class="text-center mb-4">📘 <span class="fw-bold">BookingApp</span></h5>
    <a href="/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="/my-bookings/create"><i class="bi bi-plus-circle me-2"></i>Create Booking</a>
    <a href="/calendar"><i class="bi bi-calendar-event me-2"></i>View Calendar</a>
    <a href="/profile"><i class="bi bi-person-lines-fill me-2"></i>Profile</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm px-4 d-flex justify-content-between align-items-center" id="navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-light d-md-none" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <div class="text-white fw-semibold">
            👋 {{ Auth::user()->name }}
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-light btn-sm" id="themeToggle" title="Toggle Theme">
            <i class="bi bi-moon-stars-fill"></i>
        </button>
        <div class="dropdown">
            <button class="btn btn-light position-relative" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                @if(auth()->user()->unreadNotifications->count())
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                @forelse(auth()->user()->unreadNotifications as $notification)
                    <li class="notification-item bg-light border-bottom d-flex justify-content-between align-items-start">
                        <div>
                            <a href="/my-bookings/{{ $notification->data['booking_id'] }}" class="text-dark text-decoration-none d-block fw-semibold">
                                <i class="bi bi-dot text-danger"></i> {{ $notification->data['message'] }}
                            </a>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-link text-decoration-none p-0">✔</button>
                        </form>
                    </li>
                @empty
                    <li class="notification-item text-muted text-center">No new notifications</li>
                @endforelse
                @if(auth()->user()->unreadNotifications->count())
                    <li class="text-center py-2 border-top">
                        <form method="POST" action="{{ route('notifications.readAll') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary">Mark all as read</button>
                        </form>
                    </li>
                @endif
                @if(auth()->user()->readNotifications->count())
                    <li><hr class="dropdown-divider"></li>
                    <li class="dropdown-header d-flex justify-content-between px-3 py-2 text-muted">
                        <span>Earlier</span>
                        <form method="POST" action="{{ route('notifications.clearRead') }}">
                            @csrf
                            <button class="btn btn-sm btn-link text-danger text-decoration-none p-0">Clear All</button>
                        </form>
                    </li>
                    @foreach (auth()->user()->readNotifications as $notification)
                        <li class="notification-item small text-muted border-bottom">
                            <a href="/my-bookings/{{ $notification->data['booking_id'] }}" class="text-muted text-decoration-none d-block">
                                {{ $notification->data['message'] }}<br>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </a>
                        </li>
                    @endforeach
                @endif
                <li class="text-center py-2">
                    <a href="/notifications" class="btn btn-sm btn-outline-primary">View All Notifications</a>
                </li>
            </ul>
        </div>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn btn-outline-light">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</nav>

<!-- Content Area -->
<div class="content" id="content">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-blue border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-event me-2"></i>Total Bookings</h5>
                    <h3 class="mb-0">{{ \App\Models\Booking::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-green border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people me-2"></i>Total Users</h5>
                    <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-center fw-bold mb-4"><i class="bi bi-calendar3"></i> My Bookings</h3>

    @if ($bookings->count())
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Checked In</th>
                        <th>Checked Out</th>
                        <th>People</th>
                        <th>User</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $i => $booking)
                        <tr class="text-center">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $booking->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date)->toFormattedDateString() }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
                            <td>{{ $booking->people }}</td>
                            <td>{{ $booking->user->name ?? 'Unknown' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="/my-bookings/{{ $booking->id }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                    <a href="/my-bookings/{{ $booking->id }}/edit" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="/my-bookings/{{ $booking->id }}/delete" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> No bookings found. Click "Create Booking" to add one.
        </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('navbar').classList.toggle('shifted');
        document.getElementById('content').classList.toggle('shifted');
    }

    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        const html = document.documentElement;
        const newTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', newTheme);
        themeToggle.innerHTML = newTheme === 'dark'
            ? '<i class="bi bi-sun-fill"></i>'
            : '<i class="bi bi-moon-stars-fill"></i>';
    });
</script>
</body>
</html>