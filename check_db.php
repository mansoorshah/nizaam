<?php
// Database diagnostic script
$config = require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Database connection successful\n\n";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists\n\n";
        
        // Check for admin user
        $stmt = $pdo->prepare("SELECT id, email, role, is_active FROM users WHERE email = ?");
        $stmt->execute(['admin@nizaam.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "✅ Admin user found:\n";
            echo "   ID: {$user['id']}\n";
            echo "   Email: {$user['email']}\n";
            echo "   Role: {$user['role']}\n";
            echo "   Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n\n";
            
            // Get password hash
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE email = ?");
            $stmt->execute(['admin@nizaam.com']);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "Testing password 'admin123' against stored hash...\n";
            if (password_verify('admin123', $result['password_hash'])) {
                echo "✅ Password verification SUCCESSFUL\n";
            } else {
                echo "❌ Password verification FAILED\n";
                echo "Stored hash: " . substr($result['password_hash'], 0, 50) . "...\n\n";
                echo "Generating correct hash for 'admin123':\n";
                $correctHash = password_hash('admin123', PASSWORD_BCRYPT);
                echo "$correctHash\n\n";
                echo "UPDATE SQL:\n";
                echo "UPDATE users SET password_hash = '$correctHash' WHERE email = 'admin@nizaam.com';\n";
            }
        } else {
            echo "❌ Admin user NOT found\n";
            echo "Run this SQL to create admin user:\n\n";
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            echo "INSERT INTO users (email, password_hash, role, is_active) VALUES\n";
            echo "('admin@nizaam.com', '$hash', 'admin', TRUE);\n";
        }
        
        // Count total users
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo "\nTotal users in database: $count\n";
        
    } else {
        echo "❌ Users table does NOT exist\n";
        echo "Please run database/schema.sql first\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
