<?php
session_start(); // Start the session
include 'config.php'; // Include the database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// Get the employee email from the session
$employee_email = $_SESSION['user_email'];

// Fetch the employee ID from the database based on the email
$sql = "SELECT id FROM employee WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employee_email);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    echo "Employee not found.";
    exit();
}

$employee_id = $employee['id']; // Get the employee ID

// Fetch leave requests for the employee
$sql = "SELECT lr.id, lr.start_date, lr.end_date, lr.reason, lr.status 
        FROM leave_requests lr 
        WHERE lr.employee_id = ?
        ORDER BY lr.start_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Added viewport meta tag -->
    <title>My Leave Requests</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
</head>

<body>
    <?php require "./header.php"; ?>

    <div class="container mt-5">
        <h1 class="mb-4">My Leave Requests</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
            $counter = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($counter++) . "</td>
                            <td>" . htmlspecialchars($row['start_date']) . "</td>
                            <td>" . htmlspecialchars($row['end_date']) . "</td>
                            <td>" . htmlspecialchars($row['reason']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No leave requests found</td></tr>";
            }
            ?>
                </tbody>
            </table>
            <a href="request_leave.php" class="btn btn-primary">Request New Leave</a>
        </div>
    </div>

    <footer class="bg-light text-center p-3 mt-4">
        <?php require "./footer.php"; ?>
    </footer>
</body>

</html>