<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Calendar - BookingApp</title>
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
        #calendar {
            max-width: 1000px;
            margin: 0 auto;
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
<div class="sidebar" id="sidebar">
    <h5 class="text-center mb-4">📘 <span class="fw-bold">BookingApp</span></h5>
    <a href="/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="/my-bookings/create"><i class="bi bi-plus-circle me-2"></i>Create Booking</a>
    <a href="/calendar"><i class="bi bi-calendar-event me-2"></i>View Calendar</a>
    <a href="/profile"><i class="bi bi-person-lines-fill me-2"></i>Profile</a>
</div>

<nav class="navbar navbar-expand-lg shadow-sm px-4 d-flex justify-content-between align-items-center" id="navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-light d-md-none" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <div class="text-white fw-semibold">👋 {{ Auth::user()->name }}</div>
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

<div class="content" id="content">
    <h2 class="text-center fw-bold mb-4"><i class="bi bi-calendar-event"></i> Booking Calendar</h2>
    <div id="calendar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
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

        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap5',
            events: '/calendar/events',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                if (info.event.url) {
                    window.open(info.event.url, '_blank');
                }
            }
        });
        calendar.render();
    });

    themeToggle.addEventListener('click', () => {
        const newTheme = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });
</script>
</body>
</html>