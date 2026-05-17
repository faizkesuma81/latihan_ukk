<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // 1. Simpan ke tabel USERS terlebih dahulu
    $query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'user')";
    
    if (mysqli_query($koneksi, $query_user)) {
        // 2. Ambil ID yang baru saja dibuat di tabel users
        $id_user_baru = mysqli_insert_id($koneksi);

        // 3. Simpan ke tabel ALUMNI dengan id_user yang didapat tadi
        $query_alumni = "INSERT INTO alumni (nama, jurusan, angkatan, id_user) 
                         VALUES ('$nama', '$jurusan', '$angkatan', '$id_user_baru')";
        
        if (mysqli_query($koneksi, $query_alumni)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login'); window.location='login.php';</script>";
        } else {
            echo "Error Alumni: " . mysqli_error($koneksi);
        }
    } else {
        echo "Error User: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Alumni | Telkom Schools</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px 0; }
        .card { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        .logo { width: 60px; display: block; margin: 0 auto 15px; }
        h2 { text-align: center; color: #1e293b; margin-bottom: 30px; font-weight: 700; }
        label { display: block; font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px; }
        input, select { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; transition: 0.3s; }
        input:focus { outline: none; border-color: #1e40af; box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1); }
        hr { border: 0; border-top: 1px solid #f1f5f9; margin: 20px 0; }
        button { width: 100%; padding: 14px; background: #1e40af; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        button:hover { background: #1e3a8a; transform: translateY(-1px); }
        .footer-link { text-align: center; margin-top: 20px; font-size: 13px; color: #64748b; }
        .footer-link a { color: #1e40af; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <img src="logo-telkom-schools.png" class="logo" alt="Logo">
        <h2>Registrasi Alumni</h2>
        
        <form method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Contoh: ahmad faiz" required>

            <label>Program Keahlian (Jurusan)</label>
            <select name="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="Rekayasa Perangkat Lunak">RPL</option>
                <option value="Teknik Komputer Jaringan">TKJ</option>
                <option value="Teknik Jaringan Akses Telekomunikasi">TJAT</option>
                <option value="Animasi">Animasi</option>
            </select>

            <label>Tahun Lulus (Angkatan)</label>
            <input type="number" name="angkatan" placeholder="Contoh: 2026" required>

            <hr>

            <label>Username (Untuk Login)</label>
            <input type="text" name="username" placeholder="Masukkan username" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="······" required>

            <button type="submit" name="register">Daftar Akun</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>