<?php
// attendance.php

// Include database connection and log function
require_once 'db_connect.php';
require_once 'log_action.php';

// Check if GET request is received with user_id from the scanned QR code
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Find the ticket for the given user_id
    $sql = "SELECT * FROM tickets WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Update the ticket to mark the user as present
        $sql = "UPDATE tickets SET is_present = 1 WHERE user_id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            // Log attendance action
            log_action('Attendance marked', "User ID: $user_id");

            // Display success message
            echo "
            <div class='container mt-5'>
                <h1 class='text-success'>Attendance marked successfully!</h1>
                <a href='index.php' class='btn btn-primary mt-3'>Go back</a>
            </div>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "No ticket found for this user.";
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
