<?php
// Start session at the beginning of the file
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $event = $_POST['event'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Server-side validation
    $errors = [];
    
    // First Name validation
    if (empty($first_name)) {
        $errors[] = "First Name is required!";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
        $errors[] = "First Name should contain only letters!";
    }
    
    // Last Name validation
    if (empty($last_name)) {
        $errors[] = "Last Name is required!";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
        $errors[] = "Last Name should contain only letters!";
    }
    
    // Contact validation
    if (empty($contact)) {
        $errors[] = "Contact Number is required!";
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $errors[] = "Contact Number must be a valid 10-digit number!";
    }
    
    // Email validation
    if (empty($email)) {
        $errors[] = "Email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }
    
    // Event validation
    if (empty($event)) {
        $errors[] = "Please select an event!";
    }
    
    // Password validation
    if (empty($password)) {
        $errors[] = "Password is required!";
    } else {
        // Enhanced password validation
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long!";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter!";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter!";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one number!";
        }
    }
    
    // Confirm password validation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }
    
    // Check if the data directory exists, if not create it
    $data_dir = "user_data";
    if (!file_exists($data_dir)) {
        mkdir($data_dir, 0777, true);
    }
    
    // Check if email already exists
    $users_file = $data_dir . "/users.json";
    $users = [];
    
    if (file_exists($users_file)) {
        $users_json = file_get_contents($users_file);
        $users = json_decode($users_json, true) ?: [];
    }
    
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $errors[] = "Email already exists!";
            break;
        }
    }
    
    // If there are errors, store them in session and redirect back
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        $_SESSION['form_data'] = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'contact' => $contact,
            'email' => $email,
            'event' => $event
        ];
        header("Location: register.php");
        exit();
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Create new user
    $new_user = [
        'id' => uniqid(),
        'first_name' => $first_name,
        'last_name' => $last_name,
        'contact' => $contact,
        'email' => $email,
        'event' => $event,
        'password' => $hashed_password,
        'created_at' => date('Y-m-d H:i:s'),
        'profile_picture' => '' // Will be updated when user uploads a profile picture
    ];
    
    // Add user to array
    $users[] = $new_user;
    
    // Save to file
    file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
    
    // Near the end of the file, after saving user data
    
    
    // Set success message in session
    $_SESSION['login_success'] = "Registration successful! Your account has been created.";
    header("Location: login.php");
    exit();
}
?>