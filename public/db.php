<?php
// MariaDB / MySQL test through PDO. Connection details come from the bound parameter store as env vars.
// ?action=write creates the table if needed and inserts one row. ?action=read (default) only reads.
header('Content-Type: application/json');
$out = ['pdo_mysql_loaded' => extension_loaded('pdo_mysql')];
try {
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASSWORD');
    $out['env_present'] = [
        'DB_HOST' => $host !== false && $host !== '',
        'DB_PORT' => getenv('DB_PORT') !== false,
        'DB_NAME' => $name !== false && $name !== '',
        'DB_USER' => $user !== false && $user !== '',
        'DB_PASSWORD' => $pass !== false && $pass !== '',
    ];
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    $out['server_version'] = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    if (($_GET['action'] ?? 'read') === 'write') {
        $pdo->exec('CREATE TABLE IF NOT EXISTS gap_rows (id INT AUTO_INCREMENT PRIMARY KEY, note VARCHAR(200) NOT NULL, created_at DATETIME NOT NULL) ENGINE=InnoDB');
        $stmt = $pdo->prepare('INSERT INTO gap_rows (note, created_at) VALUES (?, UTC_TIMESTAMP())');
        $stmt->execute([substr((string)($_GET['note'] ?? 'gap test row'), 0, 200)]);
        $out['inserted_id'] = (int)$pdo->lastInsertId();
    }
    $out['rows'] = $pdo->query('SELECT id, note, created_at FROM gap_rows ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
    $out['ok'] = true;
} catch (Throwable $e) {
    http_response_code(500);
    $out['ok'] = false;
    $out['error_class'] = get_class($e);
    // The message can contain the host and user, never the password.
    $out['error'] = $e->getMessage();
}
echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
