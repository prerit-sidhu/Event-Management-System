<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get user data
$user = $_SESSION['user'];

// Get any messages from session
$success_message = $_SESSION['profile_success'] ?? '';
$error_message = $_SESSION['profile_error'] ?? '';

// Clear session messages
unset($_SESSION['profile_success']);
unset($_SESSION['profile_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Event Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .profile-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid #4CAF50;
        }
        .profile-info {
            flex: 1;
        }
        .profile-name {
            font-size: 24px;
            margin-bottom: 5px;
            color: #333;
        }
        .profile-event {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .profile-details {
            margin-top: 20px;
        }
        .detail-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s, transform 0.2s;
        }
        .logout-btn:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        .upload-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .file-input-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .file-name {
            margin-left: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="profile-container page-transition">
        <div class="profile-card">
            <h2>Welcome to Event Management System</h2>
            
            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-header">
                <img src="<?php echo !empty($user['profile_picture']) ? $user['profile_picture'] : 'uploads/default-profile.png'; ?>" 
                     alt="Profile Picture" class="profile-picture">
                <div class="profile-info">
                    <h3 class="profile-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <div class="profile-event"><?php echo htmlspecialchars($user['event']); ?></div>
                    <p>Member since: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
            
            <div class="profile-details">
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact:</span>
                    <span><?php echo htmlspecialchars($user['contact']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Event:</span>
                    <span><?php echo htmlspecialchars($user['event']); ?></span>
                </div>
            </div>
            
            <div class="upload-form">
                <h3>Update Profile Picture</h3>
                <form action="upload_profile.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="file-input-wrapper">
                            <button class="file-input-button">Choose File</button>
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                        </div>
                        <span class="file-name" id="file-name">No file chosen</span>
                    </div>
                    <button type="submit" class="btn">Upload Picture</button>
                </form>
            </div>
            
            <div style="margin-top: 20px; text-align: right;">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Display file name when selected
        document.getElementById('profile_picture').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
        });
    });
    </script>
</body>
</html>