<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticket Booking</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Event Ticket Booking Form</h1>
        <form action="generate_ticket.php" method="POST" class="mt-3">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="roll_no">Roll No:</label>
                <input type="text" name="roll_no" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Ticket</button>
        </form>
    </div>
</body>
</html>
