<?php
session_start();

// Path data files
define('DATA_PATH', __DIR__ . '/data/');
define('USERS_FILE', DATA_PATH . 'users.json');
define('RUJUKAN_FILE', DATA_PATH . 'rujukan.json');

// Buat folder data jika belum ada
if (!file_exists(DATA_PATH)) {
    mkdir(DATA_PATH, 0777, true);
}

// Inisialisasi file jika belum ada
if (!file_exists(USERS_FILE)) {
    $users = [
        ['id' => 1, 'username' => 'admin_sartika', 'password' => md5('admin123'), 'role' => 'admin_sartika', 'nama' => 'Admin Klinik Sartika'],
        ['id' => 2, 'username' => 'klinik1', 'password' => md5('klinik123'), 'role' => 'klinik_lain', 'nama' => 'Klinik A']
    ];
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

if (!file_exists(RUJUKAN_FILE)) {
    file_put_contents(RUJUKAN_FILE, json_encode([], JSON_PRETTY_PRINT));
}

function getJsonData($file) {
    return json_decode(file_get_contents($file), true) ?: [];
}

function saveJsonData($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function authUser($username, $password) {
    $users = getJsonData(USERS_FILE);
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === md5($password)) {
            return $user;
        }
    }
    return false;
}

function generateId($data) {
    return empty($data) ? 1 : max(array_column($data, 'id')) + 1;
}

function getRujukan($klinik_asal = null) {
    $rujukan = getJsonData(RUJUKAN_FILE);
    if ($klinik_asal) {
        return array_filter($rujukan, fn($r) => $r['klinik_asal'] === $klinik_asal);
    }
    return $rujukan;
}
?>
