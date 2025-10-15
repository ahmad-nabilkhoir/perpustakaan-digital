<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7f9;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            flex-shrink: 0;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f5f7f9;
        }

        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }

        .nav-item {
            padding: 15px 20px;
            display: block;
            color: #ecf0f1;
            text-decoration: none;
            transition: background-color 0.2s;
            border-left: 4px solid transparent;
        }

        .nav-item:hover {
            background-color: #34495e;
            border-left-color: #3498db;
        }

        .nav-item.active {
            background-color: #34495e;
            border-left-color: #3498db;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .search-box {
            position: relative;
            margin-right: 15px;
        }

        .search-box input {
            padding: 8px 15px 8px 35px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }

        .info-box {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7f8c8d;
            font-size: 14px;
        }

        .content-box {
            background: white;
            border-radius: 6px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 6px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: white;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .book-card:hover {
            transform: translateY(-5px);
        }

        .book-cover {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }

        .book-info {
            padding: 15px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-available {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-borrowed {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .logout-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #34495e;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1 style="font-size: 20px; font-weight: 600;">Admin Panel</h1>
            </div>
            <nav>
                <a href="#" class="nav-item active">Dashboard</a>
                <a href="#" class="nav-item">Kelola Buku</a>
                <a href="#" class="nav-item">Peminjaman</a>

                <div class="logout-section">
                    <a href="#" class="nav-item">Logout</a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <h1 style="font-size: 22px; font-weight: 600; color: #2c3e50;">Dashboard Perpustakaan</h1>
                <div style="display: flex; align-items: center;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search">
                    </div>
                    <div class="info-box">
                        <div class="info-item">
                            <i class="fas fa-question-circle"></i>
                            <span>Help</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span>18:07</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>07/09/2025</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3 class="text-gray-500">Total Buku</h3>
                    <p class="text-2xl font-bold">{{ $totalBooks }}</p>
                </div>
                <div class="stat-card">
                    <h3 class="text-gray-500">Buku Tersedia</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $availableBooks }}</p>
                </div>
                <div class="stat-card">
                    <h3 class="text-gray-500">Sedang Dipinjam</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $borrowedBooks }}</p>
                </div>
            </div>

            <!-- Search Box -->
            <div class="content-box">
                <form method="GET" action="{{ route('home') }}">
                    <div class="search-box" style="width: 100%;">
                        <i class="fas fa-search"></i>
                        <input type="text" name="q" placeholder="Cari buku..." class="w-full">
                    </div>
                </form>
            </div>

            <!-- Katalog Buku -->
            <div class="content-box">
                <h2 class="mb-4 text-xl font-bold">Katalog Buku ({{ $totalBooks }} buku)</h2>
                <div class="book-grid">
                    @foreach ($books as $book)
                        <div class="book-card">
                            <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}">

                            <div class="book-info">
                                <h3 class="font-bold">{{ $book->judul }}</h3>
                                <p class="text-sm text-gray-600">oleh {{ $book->penulis }}</p>
                                <span
                                    class="status-badge {{ $book->stok > 0 ? 'status-available' : 'status-borrowed' }}">
                                    {{ $book->stok > 0 ? 'Tersedia' : 'Dipinjam' }}
                                </span>
                                <div class="mt-3">
                                    @if ($book->stok > 0)
                                        <a href="{{ route('book.show', $book->id) }}"
                                            class="text-blue-600 hover:underline">Pinjam</a>
                                    @else
                                        <span class="text-gray-400">Tidak tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple script untuk navigasi
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    navItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');

                    // Di sini bisa ditambahkan logika untuk menampilkan halaman yang sesuai
                    alert('Navigasi ke: ' + this.textContent);
                });
            });
        });
    </script>
</body>

</html>
