<?php
// Connection settings come from the container environment (see docker-compose files).
function db_connect(): mysqli {
    $host = getenv('MYSQL_HOST') ?: 'db';
    $user = getenv('MYSQL_USER') ?: 'appuser';
    $pass = getenv('MYSQL_PASSWORD') ?: 'apppass';
    $name = getenv('MYSQL_DATABASE') ?: 'mydb';
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = new mysqli($host, $user, $pass, $name);
    if ($conn->connect_error) {
        http_response_code(500);
        die('Database connection failed: ' . htmlspecialchars($conn->connect_error));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
