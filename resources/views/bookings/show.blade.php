<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Booking Details</title>
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
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" class="btn btn-outline-light">
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>
</nav>

<!-- Content -->
<div class="content" id="content">
    <div class="container py-4">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-semibold">
                    📄 Booking Details
                </div>
                <div class="card-body p-4">
                    <dl class="row">
                        <dt class="col-sm-4">Title:</dt>
                        <dd class="col-sm-8">{{ $booking->title }}</dd>

                        <dt class="col-sm-4">Date:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($booking->date)->toFormattedDateString() }}</dd>

                        <dt class="col-sm-4">Start Time:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</dd>

                        <dt class="col-sm-4">End Time:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</dd>

                        <dt class="col-sm-4">Number of People:</dt>
                        <dd class="col-sm-8">{{ $booking->people }}</dd>

                        <dt class="col-sm-4">Booked By:</dt>
                        <dd class="col-sm-8">{{ $booking->user->name ?? 'Unknown' }}</dd>
                    </dl>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="/my-bookings" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>

                        @if ($booking->user_id === auth()->id())
                            <a href="/my-bookings/{{ $booking->id }}/edit" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('navbar').classList.toggle('shifted');
        document.getElementById('content').classList.toggle('shifted');
    }
</script>
</body>
</html>