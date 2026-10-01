<?php
// Upload probe. POST a multipart field named "file". Reports what PHP received. No ini_set anywhere.
header('Content-Type: application/json');
$r = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'content_length' => $_SERVER['CONTENT_LENGTH'] ?? null,
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'display_errors' => ini_get('display_errors'),
    'log_errors' => ini_get('log_errors'),
    'post_was_dropped' => $_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && empty($_FILES)
        && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0,
];
if (isset($_FILES['file'])) {
    $f = $_FILES['file'];
    $r['file'] = [
        'name' => $f['name'],
        'error' => $f['error'],
        'size' => $f['size'],
        'md5' => $f['error'] === UPLOAD_ERR_OK ? md5_file($f['tmp_name']) : null,
    ];
}
echo json_encode($r, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
