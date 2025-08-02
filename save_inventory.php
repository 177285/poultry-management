<?php
include 'db_connect.php'; // your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $itemName    = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity    = mysqli_real_escape_string($conn, $_POST['quantity']);
    $category    = $_POST['category'];
    $expiryDate  = $_POST['expiry_date'];

    // Handle image upload
    $imageName = $_FILES['image']['name'];
    $tempName  = $_FILES['image']['tmp_name'];
    $folder    = "uploads/" . basename($imageName);

    // Ensure upload folder exists
    if (!is_dir("uploads")) {
        mkdir("uploads");
    }

    if (move_uploaded_file($tempName, $folder)) {
        // Insert into DB
        $sql = "INSERT INTO inventory (item_name, quantity, category, expiry_date, image_path)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $itemName, $quantity, $category, $expiryDate, $folder);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: inventory.html?success=1");
                exit();
            } else {
                echo "❌ DB Error: " . mysqli_stmt_error($stmt);
            }
        } else {
            echo "❌ Prepare failed: " . mysqli_error($conn);
        }
    } else {
        echo "❌ Failed to upload image.";
    }
}
?>
this is my save_inventory.php please enable it

