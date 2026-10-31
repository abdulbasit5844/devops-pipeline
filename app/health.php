<?php
// JSON endpoint used to verify the web container can reach the database container.
require __DIR__ . '/db.php';
header('Content-Type: application/json');
$conn = db_connect();
$count = $conn->query('SELECT COUNT(*) AS c FROM employees')->fetch_assoc()['c'];
echo json_encode([
    'status'         => 'ok',
    'database'       => getenv('MYSQL_DATABASE') ?: 'mydb',
    'server_version' => $conn->server_info,
    'employee_rows'  => (int)$count,
    'web_container'  => gethostname(),
], JSON_PRETTY_PRINT);
