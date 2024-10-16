<?php
session_start(); // Start the session
include 'config.php'; // Include the database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_email1'])) {
    header('Location: login.php');
    exit();
}

// Assuming the employee's email is stored in the session
$employee_email = $_SESSION['user_email'];

// Fetch employee details from the database
$sql = "SELECT e.id, e.first_name, e.last_name, e.email, 
               e.fk_departmentid, e.fk_designationid,
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

// Fetch departments and designations for dropdowns
$departments_sql = "SELECT id, name FROM department";
$departments_result = $conn->query($departments_sql);

$designations_sql = "SELECT id, name FROM designation";
$designations_result = $conn->query($designations_sql);

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $department_id = $_POST['department_id'];
    $designation_id = $_POST['designation_id'];

    // Update employee details
    $update_sql = "UPDATE employee 
                   SET first_name = ?, last_name = ?, email = ?, 
                       fk_departmentid = ?, fk_designationid = ? 
                   WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssiis", $first_name, $last_name, $email, $department_id, $designation_id, $employee_email);
    $update_stmt->execute();

    // Redirect to profile page after update
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../HRMS_LOGO.png" type="image/png">
</head>
<body>
    <?php require "./header.php"; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Edit Profile</h1>
        <form method="POST" action="">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th><label for="first_name">First Name:</label></th>
                            <td>
                                <input type="text" id="first_name" name="first_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="last_name">Last Name:</label></th>
                            <td>
                                <input type="text" id="last_name" name="last_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="email">Email:</label></th>
                            <td>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="department_id">Department:</label></th>
                            <td>
                                <select id="department_id" name="department_id" class="form-control" required>
                                    <?php while ($row = $departments_result->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['id']; ?>" 
                                            <?php echo ($row['id'] == $employee['fk_departmentid']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="designation_id">Designation:</label></th>
                            <td>
                                <select id="designation_id" name="designation_id" class="form-control" required>
                                    <?php while ($row = $designations_result->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['id']; ?>" 
                                            <?php echo ($row['id'] == $employee['fk_designationid']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <footer class="bg-light text-center p-3 mt-4 main-footer">
        <?php require "./footer.php"; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
