<?php
// verify_user.php

// Include database connection and log function
require_once 'db_connect.php';
require_once 'log_action.php';

// Check if user_id is set in the query string
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details from the database
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $roll_no = $user['roll_no'];

        // Log the action
        log_action('User verified', "User ID: $user_id, Name: $name, Roll No: $roll_no");

        // Display user details
        echo "
        <div class='container mt-5'>
            <h1 class='text-success'>User Verified!</h1>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Roll No:</strong> $roll_no</p>
        </div>";
    } else {
        echo "<div class='container mt-5'><h1 class='text-danger'>User Not Found!</h1></div>";
    }
} else {
    echo "<div class='container mt-5'><h1 class='text-danger'>No User ID Provided!</h1></div>";
}

// Close the database connection
$conn->close();
?>
