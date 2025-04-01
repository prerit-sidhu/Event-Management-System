<?php
session_start();

// Get any errors from session
$errors = $_SESSION['login_errors'] ?? [];
$form_data = $_SESSION['login_form_data'] ?? [
    'email' => ''
];
// Change this line to match what's set in register_process.php
$success_message = $_SESSION['login_success'] ?? '';

// Clear session variables after retrieving them
unset($_SESSION['login_errors']);
unset($_SESSION['login_form_data']);
unset($_SESSION['login_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container page-transition">
        <h2>Event Login</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <?php echo $error; ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <form action="login_process.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($form_data['email']); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php" class="transition-link">Register here</a></p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add transition effect when clicking on links
        document.querySelectorAll('.transition-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                
                // Fade out
                document.querySelector('.container').style.opacity = '0';
                document.querySelector('.container').style.transform = 'translateY(20px)';
                
                // Navigate after animation completes
                setTimeout(function() {
                    window.location.href = href;
                }, 300);
            });
        });
    });
    </script>
</body>
</html>