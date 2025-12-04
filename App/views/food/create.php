<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Makanan - BodyBuddy</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
    <link rel="stylesheet" href="App/assets/css/food.css">
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
                <a href="index.php?page=trainer-food" style="font-weight: bold;">Kelola Makanan</a>
                <a href="index.php?page=profile">Profile</a>
                <a href="index.php?page=consultation">Konsultasi</a>
                <a href="index.php?page=progress">Progress</a>
                <a href="index.php?page=auth&action=logout">Logout</a>
            </nav>
        </div>
    </nav>

    <div class="container">
        <a href="index.php?page=trainer-food" class="btn btn-secondary btn-small">← Kembali</a>
        
        <div class="card" style="max-width: 700px; margin: 2rem auto;">
            <h2>Tambah Makanan Baru</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=trainer-food&action=store" method="POST">
                <div class="form-group">
                    <label for="food_name">Nama Makanan *</label>
                    <input type="text" id="food_name" name="food_name" required placeholder="Contoh: Nasi Goreng">
                </div>

                <div class="form-group">
                    <label for="calories">Kalori (per 100g) *</label>
                    <input type="number" id="calories" name="calories" required min="1" placeholder="Contoh: 200">
                    <small>Jumlah kalori dalam satuan kkal</small>
                </div>

                <div class="grid" style="grid-template-columns: repeat(3, 1fr);">
                    <div class="form-group">
                        <label for="protein">Protein (gram) *</label>
                        <input type="number" step="0.1" id="protein" name="protein" required min="0" placeholder="10.5">
                    </div>

                    <div class="form-group">
                        <label for="carbs">Karbohidrat (gram) *</label>
                        <input type="number" step="0.1" id="carbs" name="carbs" required min="0" placeholder="25.0">
                    </div>

                    <div class="form-group">
                        <label for="fats">Lemak (gram) *</label>
                        <input type="number" step="0.1" id="fats" name="fats" required min="0" placeholder="5.5">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi *</label>
                    <textarea id="description" name="description" required placeholder="Contoh: Nasi goreng dengan ayam dan sayuran (per 100g)"></textarea>
                </div>

                <div style="background-color: #e8f5e9; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                    <strong>💡 Tips:</strong>
                    <ul style="margin: 0.5rem 0; padding-left: 1.5rem;">
                        <li>Gunakan porsi 100g sebagai standar</li>
                        <li>Cek informasi nutrisi pada kemasan makanan</li>
                        <li>Untuk makanan home-made, estimasi berdasarkan bahan</li>
                    </ul>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan Makanan</button>
                    <a href="index.php?page=trainer-food" class="btn btn-secondary" style="flex: 1; text-align: center; line-height: 2.5;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="app/assets/js/main.js"></script>
</body>
</html>