<?php
// Start DB connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize inputs
    $report_date = mysqli_real_escape_string($conn, $_POST['report_date']);
    $eggs        = (int) $_POST['eggs'];
    $feed_used   = mysqli_real_escape_string($conn, $_POST['feed_used']);
    $deaths      = (int) $_POST['deaths'];
    $notes       = mysqli_real_escape_string($conn, $_POST['notes']);

    // Prepare SQL
    $sql = "INSERT INTO reports (report_date, eggs, feed_used, deaths, notes) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        // Corrected: feed_used is a string, not integer
        mysqli_stmt_bind_param($stmt, "sssis", $report_date, $eggs, $feed_used, $deaths, $notes);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect to form page with success flag
            header("Location: report.html?success=1");
            exit();
        } else {
            echo "❌ Database Error: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "❌ Prepare Failed: " . mysqli_error($conn);
    }
}
?>
