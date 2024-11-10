<?php
// download_ticket.php

require_once 'fpdf/fpdf.php'; // Include FPDF
require_once 'db_connect.php'; // Include database connection
require_once 'phpqrcode/qrlib.php'; // Include QR code library

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details from the database
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $roll_no = $user['roll_no'];

        // Generate QR code containing the URL for verification
        $qr_data = "http://localhost/Qr_web/verify_user.php?user_id=" . $user_id;

        // Create a temporary QR code image file
        $temp_qr_file = 'qr_codes/temp_' . $user_id . '.png'; // Temporary file name
        QRcode::png($qr_data, $temp_qr_file, QR_ECLEVEL_L, 4); // Generate QR code image

        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Event Ticket', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Name: ' . $name, 0, 1);
        $pdf->Cell(0, 10, 'Roll No: ' . $roll_no, 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Scan this QR code at the event:', 0, 1);
        $pdf->Image($temp_qr_file, 30, 70, 100, 100); // Use the temporary image file

        // Output the PDF
        $pdf->Output('D', 'ticket.pdf');

        // Delete the temporary QR code file
        unlink($temp_qr_file); // Remove the temporary file

    } else {
        echo "No user found.";
    }
} else {
    echo "No user ID provided.";
}

// Close the database connection
$conn->close();
?>
