<?php
// download_ticket.php

// Include database connection and FPDF library
require_once 'db_connect.php';  // Database connection
require_once 'fpdf/fpdf.php';    // FPDF library

// Check if user_id is provided in the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details from the database
    $sql = "SELECT u.name, u.roll_no, t.qr_code FROM users u 
            JOIN tickets t ON u.id = t.user_id WHERE u.id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Create a new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Title
        $pdf->Cell(0, 10, 'Event Ticket', 0, 1, 'C');
        $pdf->Ln(10);  // Line break

        // User details
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, 'Name: ' . $row['name'], 0, 1);
        $pdf->Cell(0, 10, 'Roll No: ' . $row['roll_no'], 0, 1);
        $pdf->Ln(10);  // Line break

        // QR Code
        $pdf->Cell(0, 10, 'Scan the QR Code:', 0, 1);
        $pdf->Image($row['qr_code'], 30, $pdf->GetY(), 100, 100);  // Adjust dimensions as needed

        // Output the PDF to the browser
        $pdf->Output('D', 'Ticket_' . $row['roll_no'] . '.pdf'); // Download the PDF
    } else {
        echo "No ticket found for this user.";
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
