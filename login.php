<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Query mencari user berdasarkan username dan password
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        
        // Menyimpan data ke dalam Session
        $_SESSION['login'] = true;
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];
        
        // SANGAT PENTING: Mengambil 'user_id' dari database
        // Jika sebelumnya Anda menulis 'id_user', itu penyebab ID menjadi kosong
        $_SESSION['id_user'] = $data['user_id']; 

        // Redirect berdasarkan role
        if ($data['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Alumni System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f8fafc; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 24px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 380px; 
            text-align: center; 
        }
        img { width: 70px; margin-bottom: 20px; }
        h2 { color: #1e293b; font-weight: 700; margin: 0; }
        p.subtitle { color: #64748b; font-size: 14px; margin-bottom: 25px; }
        
        label { display: block; text-align: left; font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
        input { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 20px; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            box-sizing: border-box; 
            font-family: inherit;
        }
        input:focus { outline: none; border-color: #1e40af; box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1); }
        
        button { 
            width: 100%; 
            padding: 14px; 
            background: #1e40af; 
            color: white; 
            border: none; 
            border-radius: 12px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        button:hover { background: #1e3a8a; transform: translateY(-1px); }
        
        .error { 
            color: #ef4444; 
            font-size: 13px; 
            margin-bottom: 15px; 
            background: #fef2f2; 
            padding: 10px; 
            border-radius: 8px; 
            border: 1px solid #fee2e2; 
        }
        .footer-link { font-size: 13px; margin-top: 20px; color: #64748b; }
        .footer-link a { color: #1e40af; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <img src="logo-telkom-schools.png" alt="Logo">
        <h2>Sistem Alumni</h2>
        <p class="subtitle">Telkom Schools Management</p>
        
        <?php if(isset($error)): ?>
            <div class='error'>Username atau Password Salah!</div>
        <?php endif; ?>
        
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
            
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>

            <button type="submit" name="login">Masuk Sekarang</button>
        </form>
        
        <p class="footer-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </p>
    </div>
</body>
</html>