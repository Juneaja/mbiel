<?php
require_once 'config.php';

if(isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if($_POST) {
    $user = authUser($_POST['username'], $_POST['password']);
    if($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Sartika Lamongan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient bg-primary bg-opacity-10 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <i class="fas fa-hospital fa-4x text-primary mb-3"></i>
                            <h3 class="fw-bold text-primary">Klinik Sartika</h3>
                            <p class="text-muted">Lamongan - Sistem Rujukan</p>
                        </div>
                        
                        <?php if($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="username" required 
                                           value="admin_sartika" autocomplete="username">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" required 
                                           autocomplete="current-password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5">
                                <i class="fas fa-sign-in-alt me-2"></i>Masuk
                            </button>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <div class="divider d-flex align-items-center my-3">
                                <hr class="flex-grow-1">
                                <span class="px-3 text-muted">Akun Default</span>
                                <hr class="flex-grow-1">
                            </div>
                            <div class="row text-center">
                                <div class="col-6">
                                    <small class="text-primary fw-bold">Admin</small><br>
                                    <code>admin_sartika</code><br>
                                    <code>admin123</code>
                                </div>
                                <div class="col-6">
                                    <small class="text-success fw-bold">Klinik</small><br>
                                    <code>klinik1</code><br>
                                    <code>klinik123</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
