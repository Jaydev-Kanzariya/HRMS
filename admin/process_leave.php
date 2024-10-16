<?php
include 'config.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];

    // Update leave request status
    $sql = "UPDATE leave_requests SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $leave_id);

    if ($stmt->execute()) {
        header("Location: view_leave_requests.php");;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!-- <a href="view_leave_requests.php">Back to Leave Requests</a> -->
