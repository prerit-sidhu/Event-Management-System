<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        $allowed_types = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_type = $_FILES["profile_picture"]["type"];
        $file_size = $_FILES["profile_picture"]["size"];
        
        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['profile_error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            header("Location: home.php");
            exit();
        }
        
        // Validate file size
        if ($file_size > $max_size) {
            $_SESSION['profile_error'] = "File size must be less than 5MB.";
            header("Location: home.php");
            exit();
        }
        
        // Create uploads directory if it doesn't exist
        $upload_dir = "uploads";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate a unique filename
        $file_extension = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
        $new_filename = "profile_" . $_SESSION['user']['id'] . "_" . time() . "." . $file_extension;
        $target_file = $upload_dir . "/" . $new_filename;
        
        // Move the uploaded file
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update user's profile picture in the JSON file
            $data_dir = "user_data";
            $users_file = $data_dir . "/users.json";
            
            if (file_exists($users_file)) {
                $users_json = file_get_contents($users_file);
                $users = json_decode($users_json, true) ?: [];
                
                foreach ($users as &$user) {
                    if ($user['id'] === $_SESSION['user']['id']) {
                        // Delete old profile picture if exists
                        if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                            unlink($user['profile_picture']);
                        }
                        
                        $user['profile_picture'] = $target_file;
                        break;
                    }
                }
                
                // Save updated users data
                file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
                
                // Update session data
                $_SESSION['user']['profile_picture'] = $target_file;
                
                $_SESSION['profile_success'] = "Profile picture updated successfully!";
            } else {
                $_SESSION['profile_error'] = "Error: User data file not found.";
            }
        } else {
            $_SESSION['profile_error'] = "Error uploading file. Please try again.";
        }
    } else {
        $_SESSION['profile_error'] = "Please select a file to upload.";
    }
    
    header("Location: home.php");
    exit();
}
?>