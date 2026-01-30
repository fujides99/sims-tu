<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Informasi Sekolah</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            color: #444;
        }

        /* Navbar Glassmorphism */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
        }

        /* Hero Section */
        .hero-section {
            background: var(--primary-gradient);
            color: white;
            padding: 80px 0 100px;
            border-radius: 0 0 50px 50px;
            margin-bottom: -50px; /* Efek overlap */
            position: relative;
            overflow: hidden;
        }
        
        /* Card Styling */
        .card-custom {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        /* Table Styling */
        .table-custom thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #eee;
            color: #555;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 15px;
        }
        .table-custom tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Badge Custom */
        .badge-soft {
            padding: 8px 12px;
            border-radius: 30px;
            font-weight: 600;
        }
        .badge-soft-success { background: rgba(25, 135, 84, 0.1); color: #198754; }
        .badge-soft-warning { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
        .badge-soft-danger  { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .badge-soft-primary { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-glass sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary fs-4" href="index.php">
            <i class="bi bi-mortarboard-fill me-2"></i> SIMS <span class="text-dark">PUBLIC</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link fw-bold px-3" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link fw-bold px-3" href="siswa.php">Siswa</a></li>
                <li class="nav-item"><a class="nav-link fw-bold px-3" href="ptk.php">Guru</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-bold px-3" href="#" role="button" data-bs-toggle="dropdown">
                        Arsip Surat
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg p-2 rounded-4">
                        <li><a class="dropdown-item rounded-3 mb-1" href="surat_masuk.php"><i class="bi bi-inbox me-2 text-success"></i> Surat Masuk</a></li>
                        <li><a class="dropdown-item rounded-3" href="surat_keluar.php"><i class="bi bi-send me-2 text-danger"></i> Surat Keluar</a></li>
                    </ul>
                </li>

                <li class="nav-item ms-lg-3">
                    <a href="../auth/login.php" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        Login Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>