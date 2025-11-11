<?php

require_once __DIR__ . '/../core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT version()");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h3>Koneksi ke PostgreSQL BERHASIL ✅</h3>";
    echo "<p>Versi PostgreSQL: " . htmlspecialchars($result['version']) . "</p>";
} catch (PDOException $e) {
    echo "<h3 style='color:red;'>Koneksi GAGAL ❌</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
