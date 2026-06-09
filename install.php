<?php
$conn = new mysqli('localhost', 'root', '', 'orangutan_heaven');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "<br>Pastikan database 'orangutan_heaven' sudah dibuat dengan import zoo_db.sql");
}

$password = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("UPDATE users SET password = '$password' WHERE email = 'admin@orangutanheaven.com'");

echo "
<!DOCTYPE html>
<html>
<head>
    <title>Install - Orang Hutan Heaven</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #F0F7F4; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .box { background: white; padding: 40px; border-radius: 20px; text-align: center; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,.1); }
        h1 { color: #1B4332; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin: 20px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 10px; margin: 10px 0; text-align: left; }
        a { display: inline-block; margin-top: 20px; background: #E76F51; color: white; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: 600; }
        a:hover { background: #d45a3a; }
        code { background: #f0f0f0; padding: 2px 8px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class='box'>
        <h1>🦧 Instalasi Berhasil!</h1>
        <div class='success'>✅ Password admin berhasil di-set!</div>
        <div class='info'>
            <strong>Login Admin:</strong><br>
            Email: <code>admin@orangutanheaven.com</code><br>
            Password: <code>admin123</code>
        </div>
        <div class='info'>
            <strong>Langkah selanjutnya:</strong><br>
            1. Hapus file <code>install.php</code> ini<br>
            2. Buka website di <code>http://localhost/uts/</code><br>
            3. Login sebagai admin atau daftar akun pembeli baru
        </div>
        <a href='index.php'>🏠 Buka Website</a>
    </div>
</body>
</html>
";
$conn->close();
?>
