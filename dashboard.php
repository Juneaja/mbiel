<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Klinik Sartika Lamongan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient bg-primary shadow-lg">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">
                <i class="fas fa-hospital me-2"></i>Klinik Sartika Lamongan
            </a>
            <div class="navbar-nav ms-auto align-items-center">
                <span class="navbar-text me-3">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['nama']); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Keluar
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-bars me-2"></i>Menu</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if($role == 'admin_sartika'): ?>
                        <a href="admin_dashboard.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-tachometer-alt text-primary me-3"></i>Dashboard Admin
                        </a>
                        <?php else: ?>
                        <a href="klinik_dashboard.php" class="list-group-item list-group-item-action d-flex align-items-center active">
                            <i class="fas fa-tachometer-alt text-primary me-3"></i>Dashboard Klinik
                        </a>
                        <?php endif; ?>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-user-circle me-3"></i>Profil
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <?php if($role == 'admin_sartika'): ?>
                    <script>window.location.href='admin_dashboard.php';</script>
                <?php else: ?>
                    <?php include 'klinik_dashboard.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
