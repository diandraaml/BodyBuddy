<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Workout - BodyBuddy</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
   <link rel="stylesheet" href="App/assets/css/workout.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="App/uploads/logo.png" alt="BodyBuddy Logo" class="logo-img">
                <h1>BodyBud</h1>
            </div>
            <nav class="nav-links">
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=workout">Workout</a>
                <a href="index.php?page=food">Makanan</a>
                <a href="index.php?page=profile">Profile</a>
                <a href="index.php?page=consultation">Konsultasi</a>
                <a href="index.php?page=progress">Progress</a>
                <a href="index.php?page=auth&action=logout">Logout</a>
            </nav>
        </div>
    </nav>

    <div class="container">
        <a href="index.php?page=workout" class="btn btn-secondary btn-small">← Kembali</a>
        
        <div class="card" style="max-width: 700px; margin: 2rem auto;">
            <h2>Buat Workout Baru</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=workout&action=store" method="POST">
                <div class="form-group">
                    <label for="workout_name">Nama Workout *</label>
                    <input type="text" id="workout_name" name="workout_name" required placeholder="Contoh: Push-ups">
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi *</label>
                    <textarea id="description" name="description" required placeholder="Jelaskan cara melakukan workout ini..."></textarea>
                </div>

                <div class="form-group">
                    <label for="repetitions">Jumlah Repetisi *</label>
                    <input type="number" id="repetitions" name="repetitions" required min="1" placeholder="Contoh: 15">
                    <small>Jumlah pengulangan per set</small>
                </div>

                <div class="form-group">
                    <label for="duration_minutes">Durasi (Menit) *</label>
                    <input type="number" id="duration_minutes" name="duration_minutes" required min="1" placeholder="Contoh: 10">
                    <small>Estimasi waktu untuk menyelesaikan workout</small>
                </div>

                <div class="form-group">
                    <label for="calories_burned">Kalori yang Dibakar (per set) *</label>
                    <input type="number" id="calories_burned" name="calories_burned" required min="1" placeholder="Contoh: 50">
                    <small>Estimasi kalori yang terbakar per set</small>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan Workout</button>
                    <a href="index.php?page=workout" class="btn btn-secondary" style="flex: 1; text-align: center; line-height: 2.5;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>