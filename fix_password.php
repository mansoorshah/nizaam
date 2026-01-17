<?php
// Fix admin password
$config = require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Generate correct hash for 'admin123'
    $correctHash = password_hash('admin123', PASSWORD_BCRYPT);
    
    // Update the admin user password
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$correctHash, 'admin@nizaam.com']);
    
    echo "✅ Password updated successfully!\n\n";
    echo "You can now login with:\n";
    echo "Email: admin@nizaam.com\n";
    echo "Password: admin123\n\n";
    echo "<a href='/nizaam/public/login'>Go to Login Page</a>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
