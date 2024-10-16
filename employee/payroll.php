<?php
session_start(); // Start the session
require "config.php";

// Fetch payroll records for display
$sql = "
    SELECT p.id, e.name, p.month, p.salary 
    FROM payroll p
    JOIN employee e ON p.employee_id = e.id 
    WHERE e.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['user_email']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payroll</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
</head>
<body>
    <?php require "./header.php"; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Employee Payroll Details</h1>

        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th> <!-- Sequential Number Column -->
                        <th>Employee Name</th>
                        <th>Month</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; // Initialize a counter for the row number
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $counter++; ?></td> <!-- Display the counter -->
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['month']); ?></td>
                        <td><?php echo htmlspecialchars($row['salary']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-warning" role="alert">
            No records found.
        </div>
        <?php endif; ?>

    </div>

    <footer class="bg-light text-center p-3 mt-5">
        <?php require "./footer.php"; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the connection
$stmt->close();
$conn->close();
?>
