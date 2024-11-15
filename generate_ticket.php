<?php
// generate_ticket.php

// Include database connection, QR code library, and log function
require_once 'db_connect.php';  // Database connection
require_once 'phpqrcode/qrlib.php';  // QR Code generation library
require_once 'log_action.php';  // Logging function

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $roll_no = $_POST['roll_no'];

    // Validate input
    if (empty($name) || empty($roll_no)) {
        echo "<div class='container mt-5'>
                <h1 class='text-danger'>Invalid Input!</h1>
                <p>Name and Roll No cannot be empty.</p>
                <a href='index.php' class='btn btn-primary mt-3'>Go Back</a>
              </div>";
        exit;
    }

    // Check if the roll number already exists
    $check_sql = "SELECT * FROM users WHERE roll_no = '$roll_no'";
    $result = $conn->query($check_sql);
    
    if ($result->num_rows > 0) {
        echo "<div class='container mt-5'>
                <h1 class='text-danger'>Ticket Already Generated!</h1>
                <p>This user has already generated a ticket.</p>
                <a href='index.php' class='btn btn-primary mt-3'>Go Back</a>
              </div>";
        exit;
    }

    // Insert user details into the users table
    $sql = "INSERT INTO users (name, roll_no) VALUES ('$name', '$roll_no')";
    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id; // Get the inserted user's ID

        // Generate QR code containing user ID for verification
        $qr_data = "http://localhost/Qr_web/verify_user.php?user_id=" . $user_id;

        // Capture the QR code output as a string
        ob_start();  // Start output buffering
        QRcode::png($qr_data, null, QR_ECLEVEL_L, 4);  // Generate QR code
        $qr_image = ob_get_contents();  // Get the QR code image data
        ob_end_clean();  // End buffering

        // Base64 encode the image data
        $qr_image_base64 = base64_encode($qr_image);

        // Store the QR data (string) in the database
        $sql = "INSERT INTO tickets (user_id, qr_token) VALUES ('$user_id', '$qr_data')";
        if (!$conn->query($sql)) {
            echo "Error: " . $conn->error;
        }

        // Log the action
        log_action('Ticket generated', "User ID: $user_id, Name: $name, Roll No: $roll_no");

        // Display the ticket with Bootstrap styling and embedded QR code
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Ticket Generated</title>
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css'>
        </head>
        <body>
            <div class='container mt-5'>
                <h1 class='text-success'>Ticket Generated Successfully!</h1>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Roll No:</strong> $roll_no</p>
                <p><strong>Scan this QR code at the event:</strong></p>
                <img src='data:image/png;base64,$qr_image_base64' class='img-fluid' alt='QR Code'>
                <br><a href='index.php' class='btn btn-primary mt-3'>Generate Another Ticket</a>
                <br><a href='download_ticket.php?user_id=$user_id' class='btn btn-success mt-3'>Download Ticket</a>
            </div>
        </body>
        </html>
        ";
    } else {
        echo "<div class='container mt-5'>
                <h1 class='text-danger'>Error Generating Ticket!</h1>
                <p>" . $conn->error . "</p>
                <a href='index.php' class='btn btn-primary mt-3'>Go Back</a>
              </div>";
    }
}

// Close the database connection
$conn->close();
?>
