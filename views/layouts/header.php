<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Tata Usaha</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        /* CSS Tambahan agar layout rapi */
        body {
            overflow-x: hidden; /* Hilangkan scroll horizontal */
        }
        .main-content {
            width: 100%;
            background-color: #f8f9fa; /* Warna background abu muda */
            min-height: 100vh;
        }
    </style>
</head>
<body>
    
    <div class="d-flex">
        
        <?php include __DIR__ . '/sidebar.php'; ?>

        <div class="main-content p-4">