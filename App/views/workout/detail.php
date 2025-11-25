<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $workout['workout_name']; ?> - BodyBuddy</title>
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
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="card" style="margin-top: 1rem;">
            <h2><?php echo $workout['workout_name']; ?></h2>
            <span class="badge badge-success"><?php echo $workout['category_name']; ?></span>
            
            <div class="workout-stats" style="margin: 2rem 0;">
                <div class="workout-stat">
                    <h4>Repetisi</h4>
                    <h3><?php echo $workout['repetitions']; ?>x</h3>
                </div>
                <div class="workout-stat">
                    <h4>Durasi</h4>
                    <h3><?php echo $workout['duration_minutes']; ?> menit</h3>
                </div>
                <div class="workout-stat">
                    <h4>Kalori per Set</h4>
                    <h3><?php echo $workout['calories_burned']; ?> kal</h3>
                </div>
            </div>

            <div style="background-color: var(--light-color); padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
                <h3>Deskripsi</h3>
                <p><?php echo $workout['description']; ?></p>
            </div>

            <div style="background-color: #e8f5e9; padding: 1.5rem; border-radius: 8px; margin: 1rem 0;">
                <h3>Timer Workout</h3>
                <div style="text-align: center; margin: 1rem 0;">
                    <div id="timer-display" style="font-size: 3rem; font-weight: bold; color: var(--primary-color);">
                        0:00
                    </div>
                    <div style="margin-top: 1rem;">
                        <button onclick="startWorkoutTimer(<?php echo $workout['duration_minutes']; ?>)" class="btn btn-primary">
                            Mulai Timer
                        </button>
                        <button onclick="stopWorkoutTimer()" class="btn btn-secondary">
                            Stop
                        </button>
                    </div>
                </div>
            </div>

            <?php if ($_SESSION['role'] === 'member'): ?>
            <form action="index.php?page=workout&action=complete" method="POST">
                <input type="hidden" name="workout_id" value="<?php echo $workout['id']; ?>">
                
                <div class="form-group">
                    <label for="sets_completed">Jumlah Set yang Diselesaikan</label>
                    <input type="number" id="sets_completed" name="sets_completed" min="1" value="1" required>
                    <small>Total kalori yang akan terbakar: 
                        <span id="total-calories"><?php echo $workout['calories_burned']; ?></span> kal
                    </small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    ✓ Tandai Selesai
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="app/assets/js/main.js"></script>
    
</body>
</html>