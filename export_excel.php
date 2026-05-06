<?php
require_once 'config.php';

$rujukan = getJsonData(RUJUKAN_FILE);

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="laporan_rujukan_sartika_' . date('Y-m-d') . '.xls"');
header('Cache-Control: max-age=0');

echo "LAPORAN RUJUKAN KLNIK SARTIKA LAMONGAN\n";
echo "Tanggal Export: " . date('d F Y H:i:s') . "\n\n";
echo "NO\tTANGGAL\tNAMA PASIEN\tALAMAT\tNIK\tNO.BPJS\tTGL.LAHIR\tDIAGNOSA\tTERAPI SUDAH DIBERIKAN\tALASAN DIRUJUK\tDOKTER PENGIRIM\tSTATUS\tCATATAN ADMIN\tKLINK ASAL\n\n";

$no = 1;
foreach($rujukan as $r) {
    echo $no . "\t";
    echo date('d/m/Y H:i', strtotime($r['tanggal_rujuk'])) . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', $r['nama']) . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', substr($r['alamat'], 0, 40)) . "\t";
    echo $r['nik'] . "\t";
    echo $r['no_bpjs'] . "\t";
    echo date('d/m/Y', strtotime($r['tanggal_lahir'])) . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', substr($r['diagnosa'], 0, 25)) . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', substr($r['terapi_sudah_diberikan'], 0, 25)) . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', substr($r['alasan_dirujuk'], 0, 25)) . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', $r['dokter_pengirim']) . "\t";
    echo $r['status'] . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', $r['catatan_admin']) . "\t";
    echo str_replace(["\n", "\r", "\t"], ' ', $r['klinik_asal']) . "\n";
    $no++;
}
?>
