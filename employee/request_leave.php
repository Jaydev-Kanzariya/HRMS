<?php
session_start(); // Start the session
include 'config.php'; // Include the database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_email1'])) {
    header('Location: login.php');
    exit();
}

// Get the employee email from the session
$employee_email = $_SESSION['user_email1'];

// Fetch the employee ID based on the email
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Insert leave request
    $sql = "INSERT INTO leave_requests (employee_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $employee_id, $start_date, $end_date, $reason);
    
    // Execute the statement
    if ($stmt->execute()) {
        header('Location: ./view_my_leaves.php');
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close(); // Close the statement
}

$conn->close(); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Request Leave</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
</head>

<body>
    <?php require "./header.php"; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Request Leave</h1>
        <form action="" method="POST">
            <table class="table table-bordered table-striped">
                <tr>
                    <th><label for="start_date">Start Date:</label></th>
                    <td>
                        <input type="date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                    </td>
                </tr>
                <tr>
                    <th><label for="end_date">End Date:</label></th>
                    <td>
                        <input type="date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                    </td>
                </tr>

                <tr>
                    <th><label for="reason">Reason:</label></th>
                    <td><textarea name="reason" required></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" class="btn btn-primary" value="Submit Leave Request">
                        <a href="view_my_leaves.php" class="btn btn-secondary">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <footer class="bg-light text-center p-3 mt-4 main-footer">
        <?php require "./footer.php"; ?>
    </footer>
</body>

</html>