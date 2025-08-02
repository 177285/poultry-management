<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $email, $username, $hashedPassword, $role);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['username'] = $username;
            $_SESSION['role']     = $role;

            if ($role === "admin") {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            echo "❌ Failed to register: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "❌ Database error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
