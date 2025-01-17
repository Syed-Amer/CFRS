<?php
session_start();

require('config.php'); // Include your database connection code here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify user credentials based on the extended user table structure
    $query = "SELECT id, username, password, role, full_name, email, is_admin FROM users WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    if ($result && $user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = ($user['is_admin'] == 1) ? 1 : 0;

            // Redirect to the appropriate dashboard based on the user's role
            if ($_SESSION['is_admin'] == 1) {
                header('Location: admin_dashboard.php'); // Redirect admin to admin dashboard
                exit();
            } else {
                header('Location: user_dashboard.php'); // Redirect regular user to user dashboard
                exit();
            }
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "User not found.";
    }

    // If the verification fails, you can redirect back to the login page with an error message
    header("Location: login.php?error=" . urlencode($error_message));
    exit();
}
?>
