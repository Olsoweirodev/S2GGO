<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root"; // Varsayılan XAMPP MySQL kullanıcı adı
$password = ""; // Varsayılan XAMPP MySQL şifresi
$dbname = "s2gforum_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8mb4");
} catch(PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

// Form verilerini al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    // Basit doğrulama
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Lütfen tüm alanları doldurun.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi girin.";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            $stmt->execute();
            $success = "Mesajınız başarıyla gönderildi!";
        } catch(PDOException $e) {
            $error = "Mesaj gönderilirken hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesaj Gönderildi - S2G Topluluk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #9147FF;
            --bg-dark: #0A0A0A;
            --text-primary: #F5F5F5;
            --text-secondary: #D7DADC;
            --radius-md: 8px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            background-color: #1A1A1B;
            border-radius: var(--radius-md);
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }
        
        h1 {
            font-family: 'Oxanium', sans-serif;
            font-size: 1.75rem;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 1rem;
            margin-bottom: 20px;
        }
        
        .success {
            color: #00D1B2;
        }
        
        .error {
            color: #FF4D4D;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn:hover {
            background-color: #7B3AED;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mesaj Gönderildi</h1>
        <?php if (isset($success)): ?>
            <p class="message success"><?php echo htmlspecialchars($success); ?></p>
        <?php elseif (isset($error)): ?>
            <p class="message error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <a href="about.html#contact" class="btn">Geri Dön</a>
    </div>
</body>
</html>