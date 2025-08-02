<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                // ✅ Store session data
                $_SESSION['username'] = $row['username'];
                $_SESSION['role']     = $row['role'];

                // ✅ Redirect based on role
                if ($row['role'] === 'admin') {
                    header("Location: admin-dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                echo "❌ Incorrect password.";
            }
        } else {
            echo "❌ No user found with that email.";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
