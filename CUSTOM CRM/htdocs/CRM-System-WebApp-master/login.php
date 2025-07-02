<?php
session_start();
include_once 'inc/db.php'; // Adjust path if needed

// Check if user is already logged in
if (isset($_SESSION['user_role'])) {
    header('Location: sales/tasks.php'); // Redirect to task.php in sales folder
    exit();
}

$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple authentication logic
    if ($username === 'manager' && $password === 'manager') {
        $_SESSION['user_role'] = 'manager';
        $_SESSION['username'] = 'manager';
        header('Location: sales/tasks.php'); // Redirect to task.php in sales folder
        exit();
    } elseif ($username === 'sales' && $password === 'sales') {
        $_SESSION['user_role'] = 'sales';
        $_SESSION['username'] = 'sales';
        header('Location: sales/tasks.php'); // Redirect to task.php in sales folder
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ditto Custom CRM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="logo">
                <h2>💎Ditto Custom CRM💎</h2>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            
            <div class="mt-3 text-center">
                <small class="text-muted">
                    <strong>🚀 Ditto Demo Login Details 🚀</strong><br>
                    👑 Manager: U/P: manager<br>
                    ♟️Sales: U/P: sales <br>
                    ~ Built with 💙 by Anirudh ~
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>