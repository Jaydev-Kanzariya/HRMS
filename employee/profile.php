<?php
session_start(); // Start the session
include 'config.php'; // Include the database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// Assuming the employee's email is stored in the session
$employee_email = $_SESSION['user_email1'];

// Fetch employee details from the database
$sql = "SELECT e.id, e.first_name, e.last_name, e.email, 
               d.name AS department_name, des.name AS designation_name 
        FROM employee e
        LEFT JOIN department d ON e.fk_departmentid = d.id
        LEFT JOIN designation des ON e.fk_designationid = des.id
        WHERE e.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $employee_email);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Check if employee data is retrieved
if (!$employee) {
    echo "Employee not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
</head>
<body>
    <?php require "./header.php"; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Employee Profile</h1>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>Name:</th>
                    <td><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></td>
                </tr>
                <tr>
                    <th>First Name:</th>
                    <td><?php echo htmlspecialchars($employee['first_name']); ?></td>
                </tr>
                <tr>
                    <th>Last Name:</th>
                    <td><?php echo htmlspecialchars($employee['last_name']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($employee['email']); ?></td>
                </tr>
                <tr>
                    <th>Designation:</th>
                    <td><?php echo htmlspecialchars($employee['designation_name']); ?></td>
                </tr>
                <tr>
                    <th>Department:</th>
                    <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                </tr>
            </tbody>
        </table>
        <div>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
    <footer class="bg-light text-center p-3 mt-4 main-footer">
        <?php require "./footer.php"; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
