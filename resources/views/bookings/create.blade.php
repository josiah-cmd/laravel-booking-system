<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Create Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <style>
        body {
            background-color: var(--bs-body-bg);
        }
        .flatpickr-calendar {
            font-size: 1.1rem !important;
            transform: scale(1.15);
            transform-origin: top left;
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
            z-index: 1040;
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
        }
        .content {
            margin-left: 250px;
            padding: 20px;
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
        <button class="btn btn-outline-light" id="themeToggle" title="Toggle Theme">
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
<div class="content container py-5" id="content">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="mb-4 text-center fw-bold">➕ New Booking</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Please fix the following:
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <!-- Left Column: Form Inputs -->
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="/my-bookings/create">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Booking Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Number of People</label>
                                    <input type="number" name="people" class="form-control" min="1" max="100" value="{{ old('people', 1) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Start Time</label>
                                    <input type="text" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">End Time</label>
                                    <input type="text" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
                                </div>

                                <input type="hidden" name="date" id="selected_date" value="{{ old('date') }}" required>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="/my-bookings" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Booking</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Calendar -->
                <div class="col-md-5">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="calendar" class="form-label fw-semibold">📅 Select Booking Date</label>
                                <div id="calendar" class="border rounded p-2 bg-white"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const bookedDates = @json($bookedDates ?? []);

    flatpickr("#calendar", {
        inline: true,
        dateFormat: "Y-m-d",
        minDate: "2025-01-01",
        maxDate: "2025-12-31",
        disable: bookedDates,
        allowInput: false,
        onChange: function(selectedDates, dateStr, instance) {
            document.getElementById('selected_date').value = dateStr;
        }
    });

    flatpickr("#start_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false
    });

    flatpickr("#end_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false
    });

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