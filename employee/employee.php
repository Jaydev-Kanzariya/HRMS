<?php
require "config.php";
session_start();

$email = $_SESSION["user_email"];
if ($email == NULL) {
    header("Location: login.php");
    exit();
}

// Prepare and execute SQL statement
$stmt = $conn->prepare("
    SELECT 
        e.name,
        COUNT(l.id) AS total_leaves,
        COALESCE(SUM(CASE WHEN l.status = 'upcoming' THEN 1 ELSE 0 END), 0) AS upcoming_leaves,
        COALESCE(SUM(CASE WHEN l.status = 'approved' THEN 1 ELSE 0 END), 0) AS approved_leaves,
        COALESCE(SUM(CASE WHEN l.status = 'rejected' THEN 1 ELSE 0 END), 0) AS rejected_leaves
    FROM 
        employee e
    LEFT JOIN 
        leave_requests l ON e.id = l.employee_id
    WHERE 
        e.email = ?
    GROUP BY 
        e.name
");

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc();
    $employee_name = htmlspecialchars($employee['name']);
    $total_leaves = htmlspecialchars($employee['total_leaves']);
    $upcoming_leaves = htmlspecialchars($employee['upcoming_leaves']);
    $approved_leaves = htmlspecialchars($employee['approved_leaves']);
    $rejected_leaves = htmlspecialchars($employee['rejected_leaves']);
} else {
    echo "No user found for email: " . htmlspecialchars($email);
    die();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
    <title>Employee Dashboard</title>
</head>
<body>
    <?php require "./header.php"; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Welcome, <?php echo $employee_name; ?></h1>

        <div class="row">
            <div class="col-12 col-md-6 mb-4"> <!-- Full width on small screens -->
                <h4>Your Statistics</h4>
                <ul class="list-group">
                    <li class="list-group-item">Total Leaves Taken: <?php echo $total_leaves; ?></li>
                    <li class="list-group-item">Upcoming Leaves: 10</li>
                </ul>
            </div>
            <div class="col-12 col-md-6 mb-4"> <!-- Full width on small screens -->
                <h4>Leave Requests</h4>
                <ul class="list-group">
                    <li class="list-group-item">Approved Requests: <?php echo $approved_leaves; ?></li>
                    <li class="list-group-item">Rejected Requests: <?php echo $rejected_leaves; ?></li>
                </ul>
            </div>
        </div>
    </div>

    <footer class="text-center p-3 mt-4">
        <?php require "./footer.php"; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
