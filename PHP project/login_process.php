<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Server-side validation
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required!";
    }
    
    // If there are validation errors, redirect back with error message
    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_form_data'] = [
            'email' => $email
        ];
        header("Location: login.php");
        exit();
    }
    
    // Check if the data directory and users file exists
    $data_dir = "user_data";
    $users_file = $data_dir . "/users.json";
    
    if (!file_exists($users_file)) {
        $_SESSION['login_errors'] = ["No user accounts found. Please register first."];
        header("Location: login.php");
        exit();
    }
    
    // Get users data
    $users_json = file_get_contents($users_file);
    $users = json_decode($users_json, true) ?: [];
    
    // Check if user exists and password is correct
    $user_found = false;
    $authenticated = false;
    $current_user = null;
    
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $user_found = true;
            
            if (password_verify($password, $user['password'])) {
                $authenticated = true;
                $current_user = $user;
                break;
            }
        }
    }
    
    // Handle authentication result
    if (!$user_found) {
        $_SESSION['login_errors'] = ["Email not found. Please check your email or register."];
        $_SESSION['login_form_data'] = [
            'email' => $email
        ];
        header("Location: login.php");
        exit();
    } elseif (!$authenticated) {
        $_SESSION['login_errors'] = ["Incorrect password. Please try again."];
        $_SESSION['login_form_data'] = [
            'email' => $email
        ];
        header("Location: login.php");
        exit();
    } else {
        // Store user data in session (except password)
        $_SESSION['user'] = [
            'id' => $current_user['id'],
            'first_name' => $current_user['first_name'],
            'last_name' => $current_user['last_name'],
            'contact' => $current_user['contact'],
            'email' => $current_user['email'],
            'event' => $current_user['event'],
            'profile_picture' => $current_user['profile_picture'] ?? '',
            'created_at' => $current_user['created_at']
        ];
        
        // Successful login
        $_SESSION['login_success'] = "Login successful!";
        
        // Redirect to home page
        header("Location: home.php");
        exit();
    }
}
?>