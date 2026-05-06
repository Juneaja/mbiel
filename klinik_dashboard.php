<?php
require_once 'config.php';

$success = '';
if($_POST && isset($_POST['submit_rujukan'])) {
    $rujukan = getJsonData(RUJUKAN_FILE);
    $newRujukan = [
        'id' => generateId($rujukan),
        'nama' => $_POST['nama'],
        'alamat' => $_POST['alamat'],
        'nik' => $_POST['nik'],
        'no_bpjs' => $_POST['no_bpjs'],
        'tanggal_lahir' => $_POST['tanggal_lahir'],
        'diagnosa' => $_POST['diagnosa'],
        'terapi_sudah_diberikan' => $_POST['terapi_sudah_diberikan'],
        'alasan_dirujuk' => $_POST['alasan_dirujuk'],
        'dokter_pengirim' => $_POST['dokter_pengirim'],
        'status' => 'pending',
        'catatan_admin' => '',
        'klinik_asal' => $_SESSION['nama'],
        'tanggal_rujuk' => date('Y-m-d H:i:s'),
        'tanggal_proses' => ''
    ];
    
    $rujukan[] = $newRujukan;
    saveJsonData(RUJUKAN_FILE, $rujukan);
    $success = "✅ Rujukan berhasil dikirim ke Klinik Sartika!";
}

$rujukan_saya = array_values(getRujukan($_SESSION['nama']));
?>

<div class="card shadow-lg border-0 mb-5">
    <div class="card-header bg-success bg-gradient text-white py-3">
        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Input Data Rujukan Baru</h4>
    </div>
    <div class="card-body p-4">
        <?php if($success): ?>
        <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST" id="formRujukan">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Pasien <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">NIK <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nik" maxlength="16" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">No. BPJS <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="no_bpjs" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_lahir" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="alamat" rows="2" required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Diagnosa <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="diagnosa" rows="3" required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Terapi Sudah Diberikan <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="terapi_sudah_diberikan" rows="3" required></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Alasan Dirujuk <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="alasan_dirujuk" rows="3" required></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Dokter Pengirim <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dokter_pengirim" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" name="submit_rujukan" class="btn btn-success btn-lg px-5">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Rujukan ke Sartika
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-lg border-0">
    <div class="card-header bg-info bg-gradient text-white py-3">
        <h4 class="mb-0"><i class="fas fa-list me-2"></i>Rujukan Saya (<?php echo count($rujukan_saya); ?>)</h4>
    </div>
    <div class="card-body p-0">
        <?php if(empty($rujukan_saya)): ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada rujukan</h5>
            <p class="text-muted">Buat rujukan pertama Anda di atas</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>NIK</th>
                        <th>Status</th>
                        <th>Catatan Sartika</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach(array_slice($rujukan_saya, 0, 10) as $r): ?>
                    <tr>
                        <td><small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($r['tanggal_rujuk'])); ?></small></td>
                        <td>
                            <strong><?php echo htmlspecialchars($r['nama']); ?></strong><br>
                            <small><?php echo htmlspecialchars($r['dokter_pengirim']); ?></small>
                        </td>
                        <td><code><?php echo $r['nik']; ?></code></td>
                        <td>
                            <span class="badge fs-6 px-3 py-2 bg-<?php 
                                echo $r['status']=='diterima' ? 'success' : 
                                     ($r['status']=='ditolak' ? 'danger' : 'warning'); 
                            ?>">
                                <?php echo ucfirst($r['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($r['catatan_admin']): ?>
                            <small class="text-muted"><?php echo htmlspecialchars($r['catatan_admin']); ?></small>
                            <?php else: ?>
                            <span class="badge bg-secondary">Menunggu...</span>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
