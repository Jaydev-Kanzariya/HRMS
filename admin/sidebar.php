<?php
    require "config.php";
    $active = basename($_SERVER['PHP_SELF']);

    $employeeMenuActive = in_array($active, ['Employee-data.php','employee-data.php','add_employee.php','edit_employee.php','delete_employee.php']);
    $attendenceMenuActive = in_array($active, ['mark_attendance.php','attendance_records.php']);
    $leaveMenuActive = in_array($active, ['view_leave_requests.php']);
    $payrollMenuActive = in_array($active, ['payroll_history.php','generate_payslip.php']);
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index.php"><img src="../logo.png" alt="" id="logo"></a>

    <!-- Sidebar -->
    <div class="sidebar mt-3">
        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="admin.php" class="nav-link <?= $active == 'admin.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item <?= $employeeMenuActive ? 'active' : ''; ?>">
                    <a href="#" class="nav-link <?= $employeeMenuActive ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Employee
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./Employee-data.php"
                                class="nav-link <?= $active == 'Employee-data.php' ||$active == 'employee-data.php' || $active == 'add_employee.php' || $active == 'edit_employee.php' || $active == 'delete_employee.php' ? 'active' : ''; ?>">
                                <i class="far fa-wifi-1 nav-icon"></i>
                                <p>Employee</p>
                            </a>

                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= $attendenceMenuActive ? 'active' : ''; ?>">
                    <a href="#" class="nav-link <?= $attendenceMenuActive ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Attendance
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="mark_attendance.php"
                                class="nav-link <?= $active == 'mark_attendance.php' ? 'active' : ''; ?>">
                                <i class="far fa-wifi-1 nav-icon"></i>
                                <p>Attendance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="attendance_records.php"
                                class="nav-link <?= $active == 'attendance_records.php' ? 'active' : ''; ?>">
                                <i class="far fa-wifi-1 nav-icon"></i>
                                <p>Attendance Records</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?= $leaveMenuActive ? 'active' : ''; ?>">
                    <a href="#" class="nav-link <?= $leaveMenuActive ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Leave
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="view_leave_requests.php"
                                class="nav-link <?= $active == 'view_leave_requests.php' ? 'active' : ''; ?>">
                                <i class="far fa-wifi-1 nav-icon"></i>
                                <p>Leave Requests</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item <?= $payrollMenuActive ? 'active' : ''; ?>">
                    <a href="user-data.php" class="nav-link <?= $payrollMenuActive ? 'active' : ''; ?>">
                        <i class="nav-icon fab fa-cc-paypal"></i>
                        <p>
                            Payroll
                            <i class="right fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./generate_payslip.php"
                                class="nav-link <?= $active == 'generate_payslip.php' ? 'active' : ''; ?>">
                                <i class="far fa-wifi-1 nav-icon"></i>
                                <p>Payroll Processing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./payroll_history.php"
                                class="nav-link <?= $active == 'payroll_history.php' ? 'active' : ''; ?>">
                                <i class="far fa-wifi-1 nav-icon"></i>
                                <p>Salary Details</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>