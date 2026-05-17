<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

$id_user_login = $_SESSION['id_user'];
$query_profil = mysqli_query($koneksi, "SELECT * FROM alumni WHERE id_user = '$id_user_login'");
$data_profil = mysqli_fetch_assoc($query_profil);
$my_id = ($data_profil) ? $data_profil['id_alumni'] : "";

$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
    $query_alumni = mysqli_query($koneksi, "SELECT * FROM alumni WHERE nama LIKE '%$keyword%' ORDER BY nama ASC");
} else {
    $query_alumni = mysqli_query($koneksi, "SELECT * FROM alumni ORDER BY nama ASC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Database Alumni</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            /* MENGUBAH BACKGROUND AGAR TIDAK TERLALU TEMBUS PANDANG */
            /* Nilai 0.7 memberikan lapisan putih yang lebih tebal agar gambar tidak mengganggu tulisan */
            background: linear-gradient(rgba(241, 245, 249, 0.7), rgba(241, 245, 249, 0.8)), 
                        url('foto-gedung.jpg') no-repeat center center fixed; 
            background-size: cover;
        }

        .navbar { 
            background: #ffffff; 
            padding: 12px 5%; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            position: sticky; 
            top: 0; 
            z-index: 1000;
        }

        .nav-brand { display: flex; align-items: center; gap: 12px; }
        .logo-small { width: 35px; }
        .badge-user { background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        
        .nav-right { display: flex; align-items: center; gap: 15px; }
        .btn-profile { 
            display: flex; align-items: center; gap: 10px; text-decoration: none; color: #1e293b; 
            background: #ffffff; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 12px; font-weight: 600; font-size: 14px; transition: 0.3s;
        }
        .btn-profile:hover { background: #f8fafc; border-color: #1e40af; }
        .btn-profile img { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; }
        
        .btn-logout { background: #ef4444; color: white; padding: 10px 20px; border-radius: 12px; text-decoration: none; font-size: 13px; font-weight: 700; }

        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        /* CARD TABLE DIBUAT SOLID PUTIH AGAR TIDAK TEMBUS PANDANG */
        .card-table { 
            background: #ffffff; 
            border-radius: 20px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); 
            overflow: hidden; 
            border: 1px solid rgba(255,255,255,1);
        }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; padding: 18px 20px; text-align: left; color: #64748b; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #f1f5f9; }
        td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #1e293b; background: #ffffff; }
        
        .search-input { padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 10px; width: 250px; outline: none; background: white; }
        .btn-cari { padding: 10px 20px; background: #1e40af; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; }
        .btn-reset { padding: 10px 20px; background: #64748b; color: white; text-decoration: none; border-radius: 10px; font-size: 14px; font-weight: 600; }

        .profile-td { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #f1f5f9; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <img src="logo-telkom-schools.png" class="logo-small" alt="Logo">
            <h2 style="margin:0; font-size: 18px; color: #1e293b;">Database Alumni</h2>
            <span class="badge-user">USER MODE</span>
        </div>
        <div class="nav-right">
            <?php if($my_id != ""): ?>
                <a href="edit.php?id=<?= $my_id ?>" class="btn-profile">
                    <span>Profil Saya</span>
                </a>
            <?php else: ?>
                <div style="background: #fffbeb; color: #92400e; padding: 8px 15px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #fef3c7;">
                    Data Anda Belum Terhubung (ID: <?= $id_user_login ?>)
                </div>
            <?php endif; ?>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h3 style="margin:0; color:#1e293b; background: white; padding: 10px 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">Daftar Alumni</h3>
            <form method="POST" style="display: flex; gap: 10px;">
                <input type="text" name="keyword" class="search-input" placeholder="Cari nama alumni..." value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" name="cari" class="btn-cari">Cari</button>
                <a href="dashboard_user.php" class="btn-reset">Reset</a>
            </form>
        </div>

        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Alumni</th>
                        <th>Angkatan</th>
                        <th>Program Keahlian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    if(mysqli_num_rows($query_alumni) > 0) {
                        while($r = mysqli_fetch_assoc($query_alumni)): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="font-weight: 700; color: #1e40af;"><?= htmlspecialchars($r['nama']) ?></td>
                        <td><span style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-weight: 600;"><?= $r['angkatan'] ?></span></td>
                        <td><?= $r['jurusan'] ?></td>
                    </tr>
                    <?php endwhile; 
                    } else { ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">Data alumni tidak ditemukan.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
       <footer style="position: fixed; bottom: 20px; left: 0; width: 100%; text-align: center; z-index: 999; pointer-events: none;">
    <p style="background: rgba(255,255,255,0.9); display: inline-block; padding: 10px 25px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: #475569; font-size: 13px; margin: 0; pointer-events: auto; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.3);">
        © 2026 Crafted by <strong>ahmad faiz</strong>
    </p>
</footer>
    </div>
</body>
</html>