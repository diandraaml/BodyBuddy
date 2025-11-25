<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BodyBuddy</title>
    <link rel="stylesheet" href="App/assets/css/global.css">
   <link rel="stylesheet" href="App/assets/css/auth.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <img src="App/uploads/logo.png" alt="BodyBuddy Logo" class="logo-img">
                <h1>BodyBud</h1>
            </div>
            <nav class="nav-links">
                <a href="index.php?page=home">Home</a>
                <a href="index.php?page=auth">Login</a>
                <a href="index.php?page=auth&action=register" class="btn-register">Register</a>
            </nav>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="max-width: 500px; margin: 3rem auto;">
            <h2 style="text-align:center;">Welcome Back!</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=auth&action=login" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            
            <img src="App/uploads/dumbbell.png" class="decor dumbbell-left">
            <img src="App/uploads/shoe.png" class="decor shoe-left">
            <img src="App/uploads/dumbbell.png" class="decor dumbbell-right">
            <img src="App/uploads/shoe.png" class="decor shoe-right">

            <p style="text-align: center; margin-top: 1rem;">
                Belum punya akun? <a href="index.php?page=auth&action=register">Daftar di sini</a>
            </p>
        
            
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>