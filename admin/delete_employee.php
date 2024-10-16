<?php
require "config.php";
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['fk_userid']) || !is_numeric($_GET['fk_userid'])) {
    header("Location: employee-data.php");
    exit();
}

$id = (int)$_GET['id'];
$fk_userid = (int)$_GET['fk_userid'];

try {
    mysqli_begin_transaction($conn);

    // Delete records from attendance, leave_requests, and payroll tables
    $sqlAttendance = "DELETE FROM attendance WHERE employee_id = $id";
    $sqlLeaveRequests = "DELETE FROM leave_requests WHERE employee_id = $id";
    $sqlPayroll = "DELETE FROM payroll WHERE employee_id = $id";
    
    // Execute delete queries
    $resAttendance = mysqli_query($conn, $sqlAttendance);
    $resLeaveRequests = mysqli_query($conn, $sqlLeaveRequests);
    $resPayroll = mysqli_query($conn, $sqlPayroll);

    // Delete employee and user
    $sqlEmployee = "DELETE FROM employee WHERE id = $id";
    $resEmployee = mysqli_query($conn, $sqlEmployee);

    $sqlUser = "DELETE FROM user WHERE id = $fk_userid";
    $resUser = mysqli_query($conn, $sqlUser);

    // Check if all queries were successful
    if ($resAttendance && $resLeaveRequests && $resPayroll && $resEmployee && $resUser) {
        // Commit transaction
        mysqli_commit($conn);
        $_SESSION["delete"] = true;
    } else {
        // Rollback transaction if any query failed
        mysqli_rollback($conn);
        $_SESSION["delete_error"] = "Failed to delete the records.";
    }
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION["delete_error"] = "Failed to delete the record: " . $e->getMessage();
}

header("Location: employee-data.php");
exit();
?>
