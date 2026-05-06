<?php
require_once 'config.php';

$success = '';
if($_POST) {
    $rujukan = getJsonData(RUJUKAN_FILE);
    foreach($rujukan as &$r) {
        if(isset($_POST['terima_' . $r['id']])) {
            $r['status'] = 'diterima';
            $r['catatan_admin'] = $_POST['catatan_' . $r['id']] ?? '';
            $r['tanggal_proses'] = date('Y-m-d H:i:s');
            $success = "✅ Rujukan ID {$r['id']} diterima!";
        } elseif(isset($_POST['tolak_' . $r['id']])) {
            $r['status'] = 'ditolak';
            $r['catatan_admin'] = $_POST['catatan_' . $r['id']] ?? '';
            $r['tanggal_proses'] = date('Y-m-d H:i:s');
            $success = "❌ Rujukan ID {$r['id']} ditolak!";
        }
    }
    saveJsonData(RUJUKAN_FILE, $rujukan);
}

$rujukan = array_values(getRujukan());
$stats = [
    'pending' => count(array_filter($rujukan, fn($r) => $r['status'] == 'pending')),
    'diterima' => count(array_filter($rujukan, fn($r) => $r['status'] == 'diterima')),
    'ditolak' => count(array_filter($rujukan, fn($r) => $r['status'] == 'ditolak')),
    'total' => count($rujukan)
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Klinik Sartika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'dashboard.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card shadow h-100 text-white bg-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-3x mb-3 opacity-75"></i>
                        <h2 class="display-4"><?php echo $stats['pending']; ?></h2>
                        <h6 class="mb-0">Pending</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow h-100 text-white bg-success">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                        <h2 class="display-4"><?php echo $stats['diterima']; ?></h2>
                        <h6 class="mb-0">Diterima</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow h-100 text-white bg-danger">
                    <div class="card-body text-center">
                        <i class="fas fa-times-circle fa-3x mb-3 opacity-75"></i>
                        <h2 class="display-4"><?php echo $stats['ditolak']; ?></h2>
                        <h6 class="mb-0">Ditolak</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow h-100 text-white bg-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                        <h2 class="display-4"><?php echo $stats['total']; ?></h2>
                        <h6 class="mb-0">Total</h6>
                    </div>
                </div>
            </div>
        </div>

        <?php if($success): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary bg-gradient text-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Rujukan (<?php echo $stats['total']; ?>)</h4>
                <a href="export_excel.php" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </a>
            </div>
            <div class="card-body p-0">
                <?php if(empty($rujukan)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada data rujukan</h5>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>NIK</th>
                                <th>Klinik Asal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($rujukan, 0, 20) as $r): ?>
                            <tr>
                                <td>
                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($r['tanggal_rujuk'])); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($r['nama']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($r['dokter_pengirim']); ?></small>
                                </td>
                                <td><code class="fs-6"><?php echo $r['nik']; ?></code></td>
                                <td>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($r['klinik_asal']); ?></span>
                                </td>
                                <td>
                                    <span class="badge fs-6 px-3 py-2 bg-<?php 
                                        echo $r['status']=='diterima' ? 'success' : 
                                             ($r['status']=='ditolak' ? 'danger' : 'warning'); 
                                    ?>">
                                        <?php echo ucfirst($r['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($r['status'] == 'pending'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                        <div class="input-group input-group-sm">
                                            <textarea class="form-control" name="catatan_<?php echo $r['id']; ?>" 
                                                      placeholder="Catatan proses..." rows="1" maxlength="200"></textarea>
                                            <button type="submit" name="terima_<?php echo $r['id']; ?>" 
                                                    class="btn btn-success" title="Terima">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="submit" name="tolak_<?php echo $r['id']; ?>" 
                                                    class="btn btn-danger" title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <?php else: ?>
                                    <div class="small">
                                        <span class="badge bg-secondary"><?php echo date('d/m H:i', strtotime($r['tanggal_proses'])); ?></span>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($r['catatan_admin']); ?></small>
                                    </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
