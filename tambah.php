<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Admin yang bisa menambah data melalui halaman ini
// Jika ingin User juga bisa menambah, hapus bagian: && $_SESSION['role'] == 'admin'
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') { 
    header("Location: login.php"); 
    exit; 
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $jurusan = $_POST['jurusan'];

    $query = "INSERT INTO alumni (nama, angkatan, jurusan) VALUES ('$nama', '$angkatan', '$jurusan')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data alumni berhasil ditambahkan!');
                window.location='dashboard_admin.php';
              </script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Alumni | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af;
            --bg: #f8fafc;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            border: 1px solid #f1f5f9;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 70px;
        }
        h2 {
            text-align: center;
            color: #1e293b;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }
        input, select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-sizing: border-box;
            font-family: inherit;
            font-size: 15px;
            transition: all 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: #1e3a8a;
            transform: translateY(-1px);
        }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #94a3b8;
            font-size: 14px;
            transition: 0.2s;
        }
        .btn-back:hover {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-container">
            <img src="logo-telkom-schools.png" alt="Logo">
        </div>
        <h2>Tambah Alumni</h2>
        
        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama alumni" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Angkatan (Tahun Lulus)</label>
                <input type="number" name="angkatan" placeholder="Contoh: 2024" required>
            </div>

            <div class="form-group">
                <label>Program Keahlian</label>
                <select name="jurusan" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                    <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
                    <option value="Teknik Jaringan Akses Telekomunikasi">Teknik Jaringan Akses Telekomunikasi</option>
                    <option value="Animasi">Animasi</option>
                </select>
            </div>

            <button type="submit" name="simpan" class="btn-submit">Simpan Data Alumni</button>
            <a href="dashboard_admin.php" class="btn-back">Kembali ke Dashboard</a>
        </form>
    </div>
</body>
</html>