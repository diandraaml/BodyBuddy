<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Makanan - BodyBuddy</title>
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
                <a href="index.php?page=trainer-food">Makanan</a>
                <a href="index.php?page=profile">Profile</a>
                <a href="index.php?page=consultation">Konsultasi</a>
                <a href="index.php?page=progress">Progress</a>
                <a href="index.php?page=auth&action=logout">Logout</a>
            </nav>
        </div>
    </nav>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Kelola Makanan</h2>
            <a href="index.php?page=trainer-food&action=create" class="btn btn-primary">+ Tambah Makanan Baru</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>


        <!-- Daftar Makanan -->
        <div class="card">
            <h3>Daftar Semua Makanan</h3>
            <div class="grid">
                <?php foreach ($foods as $food): ?>
                    <div class="grid-item">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h3 style="margin: 0;"><?php echo $food['food_name']; ?></h3>
                        </div>
                        
                        <div style="background-color: #f0f0f0; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                                <span>Kalori:</span>
                                <strong style="color: #4CAF50;"><?php echo $food['calories']; ?> kal</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                                <span>Protein:</span>
                                <strong><?php echo $food['protein']; ?>g</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                                <span>Karbohidrat:</span>
                                <strong><?php echo $food['carbs']; ?>g</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                                <span>Lemak:</span>
                                <strong><?php echo $food['fats']; ?>g</strong>
                            </div>
                        </div>
                        
                        <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;"><?php echo $food['description']; ?></p>
                        
                        <div style="display: flex; gap: 0.5rem;">
                            <form action="index.php?page=trainer-food&action=delete" method="POST" style="flex: 1;">
                                <input type="hidden" name="id" value="<?php echo $food['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-small" style="width: 100%;" 
                                        onclick="return confirm('Yakin ingin menghapus makanan ini? Ini akan menghapus semua catatan member yang menggunakan makanan ini.')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="app/assets/js/main.js"></script>
</body>
</html>