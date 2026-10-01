<?php
// Status page. Reports runner defaults as seen by this request, without changing any ini value.
header('Content-Type: application/json');
$keys = ['memory_limit', 'max_execution_time', 'upload_max_filesize', 'post_max_size',
    'max_file_uploads', 'display_errors', 'display_startup_errors', 'log_errors', 'error_log'];
$ini = [];
foreach ($keys as $k) {
    $ini[$k] = ini_get($k);
}
$setupLog = @file_get_contents('/tmp/osc-setup-info');
echo json_encode([
    'php_version' => PHP_VERSION,
    'sapi' => PHP_SAPI,
    'ini' => $ini,
    'loaded_ini_files' => php_ini_scanned_files(),
    'extensions_of_interest' => array_combine(
        ['pdo_mysql', 'mysqli', 'pdo_pgsql', 'gd', 'intl', 'zip'],
        array_map('extension_loaded', ['pdo_mysql', 'mysqli', 'pdo_pgsql', 'gd', 'intl', 'zip'])
    ),
    'loaded_extensions' => get_loaded_extensions(),
    'setup_info' => $setupLog === false ? null : $setupLog,
    'time' => gmdate('c'),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
