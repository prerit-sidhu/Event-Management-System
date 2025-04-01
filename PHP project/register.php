<?php
session_start();

// Get any errors from session
$errors = $_SESSION['registration_errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [
    'first_name' => '',
    'last_name' => '',
    'contact' => '',
    'email' => '',
    'event' => ''
];
$success_message = $_SESSION['registration_success'] ?? '';

// Clear session variables after retrieving them
unset($_SESSION['registration_errors']);
unset($_SESSION['form_data']);
unset($_SESSION['registration_success']);

// Event list options
$events = [
    'Dance', 'Music', 'Poetry', 'Art', 'Theater', 
    'Photography', 'Film', 'Comedy', 'Food Festival', 'Technology'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Event Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container page-transition">
        <h2>Event Registration</h2>
        
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
        
        <form action="register_process.php" method="post">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($form_data['first_name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($form_data['last_name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="contact">Contact Number:</label>
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($form_data['contact']); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($form_data['email']); ?>">
            </div>
            
            <div class="form-group event-select">
                <label for="event" class="event-select-label">Select Event:</label>
                <select id="event" name="event" class="event-dropdown">
                    <option value="">-- Select an Event --</option>
                    <?php foreach ($events as $event): ?>
                        <option value="<?php echo $event; ?>" <?php echo ($form_data['event'] === $event) ? 'selected' : ''; ?>>
                            <?php echo $event; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <small>Password must be at least 8 characters long and contain uppercase, lowercase, and numbers.</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php" class="transition-link">Login here</a></p>
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