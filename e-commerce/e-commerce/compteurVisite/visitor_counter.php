<?php
require_once(__DIR__ . '/../config/dbconnect.php');

header('Content-Type: application/json; charset=utf-8');

// This endpoint records the visitor (per session) and returns active viewers and today's unique visits
try {
    $conn = connectDB();

    // Ensure table exists
    $conn->exec("CREATE TABLE IF NOT EXISTS visitors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(128) NOT NULL,
        page VARCHAR(255) NOT NULL,
        last_seen DATETIME NOT NULL,
        created_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Use session id if available; start a session if needed
    if (session_status() === PHP_SESSION_NONE) session_start();
    $sid = session_id();

    $page = isset($_GET['page']) ? substr($_GET['page'], 0, 200) : 'home';
    $now = (new DateTime())->format('Y-m-d H:i:s');

    // Upsert visitor row for this session+page
    $sql = "SELECT id FROM visitors WHERE session_id = :sid AND page = :page";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':sid' => $sid, ':page' => $page]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $sql = "UPDATE visitors SET last_seen = :now WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':now' => $now, ':id' => $row['id']]);
    } else {
        $sql = "INSERT INTO visitors (session_id, page, last_seen, created_at) VALUES (:sid, :page, :now, :now)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':sid' => $sid, ':page' => $page, ':now' => $now]);
    }

    // Active viewers = sessions with last_seen within last 5 minutes
    $sql = "SELECT COUNT(DISTINCT session_id) as active FROM visitors WHERE page = :page AND last_seen >= (NOW() - INTERVAL 5 MINUTE)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':page' => $page]);
    $active = (int)$stmt->fetchColumn();

    // Today's unique visits
    $sql = "SELECT COUNT(DISTINCT session_id) as today FROM visitors WHERE page = :page AND DATE(created_at) = CURDATE()";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':page' => $page]);
    $today = (int)$stmt->fetchColumn();

    echo json_encode(['active' => $active, 'today' => $today]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'server error']);
}

?>
