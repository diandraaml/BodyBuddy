<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Makanan - BodyBuddy</title>
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
                <a href="index.php?page=food">Makanan</a>
                <a href="index.php?page=profile">Profile</a>
                <a href="index.php?page=consultation">Konsultasi</a>
                <a href="index.php?page=progress">Progress</a>
                <a href="index.php?page=auth&action=logout">Logout</a>
            </nav>
        </div>
    </nav>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Makanan</h2>
            <?php if ($_SESSION['role'] === 'trainer'): ?>
                <a href="index.php?page=food&action=create" class="btn btn-primary">Tambah Makanan</a>
            <?php endif; ?>
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

        <!-- Total Kalori Hari Ini -->
        <div class="card" style="background: #9BA34A; color: white; text-align: center;">
            <h2 style="margin: 0; color: white;">Total Kalori Hari Ini</h2>
            <h1 style="font-size: 3rem; margin: 1rem 0;"><?php echo $totalCalories; ?> kal</h1>
            <p style="opacity: 0.9;">Target harian: 2000 kal</p>
            <div class="progress-bar" style="background-color: rgba(255,255,255,0.3);">
                <div class="progress-fill" style="width: <?php echo min(($totalCalories / 2000) * 100, 100); ?>%; background: rgba(255,255,255,0.8); color: #333;">
                    <?php echo round(($totalCalories / 2000) * 100); ?>%
                </div>
            </div>
        </div>

        <!-- Makanan Hari Ini -->
        <?php if ($_SESSION['role'] === 'member'): ?>
        <div class="card">
            <h3>Makanan yang Sudah Dimakan Hari Ini</h3>
            <?php if (count($userFoods) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Makanan</th>
                            <th>Jumlah</th>
                            <th>Kalori Satuan</th>
                            <th>Total Kalori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userFoods as $uf): ?>
                            <tr>
                                <td><?php echo $uf['food_name']; ?></td>
                                <td><?php echo $uf['quantity']; ?>x</td>
                                <td><?php echo $uf['calories']; ?> kal</td>
                                <td><strong><?php echo $uf['total_calories']; ?> kal</strong></td>
                                <td>
                                    <form action="index.php?page=food&action=delete" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $uf['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-small btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada makanan yang ditambahkan hari ini.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Daftar Makanan -->
        <div class="card">
            <h3>Daftar Makanan Tersedia</h3>
            <div class="grid">
                <?php foreach ($foods as $food): ?>
                    <div class="grid-item">
                        <h3><?php echo $food['food_name']; ?></h3>
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
                        <p style="font-size: 0.9rem; color: #666;"><?php echo $food['description']; ?></p>
                        
                        <?php if ($_SESSION['role'] === 'member'): ?>
                        <form action="index.php?page=food&action=add" method="POST" style="margin-top: 1rem;">
                            <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                            <div class="form-group" style="margin-bottom: 0.5rem;">
                                <label for="quantity_<?php echo $food['id']; ?>">Jumlah Porsi:</label>
                                <input type="number" id="quantity_<?php echo $food['id']; ?>" name="quantity" 
                                       min="1" value="1" class="food-quantity" data-calories="<?php echo $food['calories']; ?>" required>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <small>Total: <span class="total-calories"><?php echo $food['calories']; ?> kal</span></small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-small" style="width: 100%;">Tambahkan</button>
                        </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="app/assets/js/main.js"></script>
</body>
</html>