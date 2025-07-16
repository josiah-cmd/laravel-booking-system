<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>All Notifications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease;
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
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn btn-outline-light">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</nav>

<!-- Content -->
<div class="content" id="content">
    <h2 class="mb-4">🔔 All Notifications</h2>

    @if ($notifications->count())
        <ul class="list-group shadow-sm">
            @foreach ($notifications as $note)
                <li class="list-group-item d-flex justify-content-between align-items-start {{ $note->read_at ? '' : 'bg-light' }}">
                    <div>
                        <a href="/my-bookings/{{ $note->data['booking_id'] }}"
                           class="text-decoration-none {{ $note->read_at ? 'text-muted' : 'fw-bold' }}">
                            {{ $note->data['message'] }}
                        </a>
                        <br>
                        <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                    </div>
                    @if (!$note->read_at)
                        <form method="POST" action="{{ route('notifications.read', $note->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-success">Mark as read</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">You have no notifications.</div>
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
    const html = document.documentElement;

    function applyTheme(theme) {
        html.setAttribute('data-bs-theme', theme);
        themeToggle.innerHTML = theme === 'dark'
            ? '<i class="bi bi-sun-fill"></i>'
            : '<i class="bi bi-moon-stars-fill"></i>';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);
    });

    themeToggle.addEventListener('click', () => {
        const newTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });
</script>
</body>
</html>