<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout - BodyBuddy</title>
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Daftar Workout</h2>
            <?php if ($_SESSION['role'] === 'trainer'): ?>
                <a href="index.php?page=workout&action=create" class="btn btn-primary">Tambah Workout</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="category-filter">
            <label class="kategori-label">Kategori :</label>
            
            <select id="categorySelect" onchange="location = this.value;">
                <option value="index.php?page=workout&category=all"
                    <?php echo (!isset($_GET['category']) || $_GET['category'] === 'all') ? 'selected' : ''; ?>>
                    Semua
                </option>

                <?php foreach ($categories as $cat): ?>
                    <option value="index.php?page=workout&category=<?php echo $cat['id']; ?>"
                        <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo $cat['category_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>




        <div class="grid">
            <?php foreach ($workouts as $workout): ?>
                <div class="grid-item">
                    <h3><?php echo $workout['workout_name']; ?></h3>
                    <span class="badge <?php echo strtolower($workout['category_name']); ?>">
                        <?php echo $workout['category_name']; ?>
                    </span>

                    <p style="margin: 1rem 0;"><?php echo substr($workout['description'], 0, 100); ?>...</p>
                    
                    <div style="display: flex; justify-content: space-between; margin: 1rem 0; font-size: 0.9rem;">
                        <div>
                            <strong>Repetisi:</strong> <?php echo $workout['repetitions']; ?>x
                        </div>
                        <div>
                            <strong>Durasi:</strong> <?php echo $workout['duration_minutes']; ?> mnt
                        </div>
                        <div>
                            <strong>Kalori:</strong> <?php echo $workout['calories_burned']; ?> kal
                        </div>
                    </div>
                    
                    <a href="index.php?page=workout&action=detail&id=<?php echo $workout['id']; ?>" 
                       class="btn btn-primary btn-small" style="width: 100%;">
                        Lihat Detail
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($workouts) === 0): ?>
            <div class="card">
                <p style="text-align: center;">Belum ada workout tersedia untuk kategori ini.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="App/assets/js/main.js"></script>
</body>
</html>