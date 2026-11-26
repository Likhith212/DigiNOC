<?php
$user = current_user();
$role = $user['role'] ?? '';
$prefixes = [
    'Student' => 'student',
    'Faculty' => 'faculty',
    'HOD' => 'hod',
    'Principal' => 'principal',
    'Super Admin' => 'admin',
];
$prefix = $prefixes[$role] ?? '';
?>
<aside class="sidebar" id="sidebar">
    <a class="brand" href="<?= e($base) ?>/<?= e($prefix) ?>/dashboard.php"><span>CLEAR</span>X</a>
    <nav class="side-nav">
        <?php if ($role === 'Student'): ?>
            <a href="<?= e($base) ?>/student/dashboard.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
            <a href="<?= e($base) ?>/student/profile.php"><i class="fa-solid fa-user"></i>My Profile</a>
            <a href="<?= e($base) ?>/student/request_clearance.php"><i class="fa-solid fa-file-circle-plus"></i>New Request</a>
            <a href="<?= e($base) ?>/student/clearance_status.php"><i class="fa-solid fa-route"></i>Clearance Status</a>
            <a href="<?= e($base) ?>/student/notifications.php"><i class="fa-solid fa-bell"></i>Notifications</a>
        <?php elseif ($role === 'Faculty'): ?>
            <a href="<?= e($base) ?>/faculty/dashboard.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
            <a href="<?= e($base) ?>/faculty/students.php"><i class="fa-solid fa-users"></i>Students</a>
            <a href="<?= e($base) ?>/faculty/pending_requests.php"><i class="fa-solid fa-hourglass-half"></i>Pending Requests</a>
        <?php elseif ($role === 'HOD'): ?>
            <a href="<?= e($base) ?>/hod/dashboard.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
            <a href="<?= e($base) ?>/hod/department_requests.php"><i class="fa-solid fa-building"></i>Department Requests</a>
        <?php elseif ($role === 'Principal'): ?>
            <a href="<?= e($base) ?>/principal/dashboard.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
            <a href="<?= e($base) ?>/principal/final_approvals.php"><i class="fa-solid fa-stamp"></i>Final Approvals</a>
        <?php else: ?>
            <a href="<?= e($base) ?>/admin/dashboard.php"><i class="fa-solid fa-grid-2"></i>Dashboard</a>
            <a href="<?= e($base) ?>/admin/users.php"><i class="fa-solid fa-users-gear"></i>Users</a>
            <a href="<?= e($base) ?>/admin/departments.php"><i class="fa-solid fa-building"></i>Departments</a>
            <a href="<?= e($base) ?>/admin/reports.php"><i class="fa-solid fa-chart-pie"></i>Reports</a>
            <a href="<?= e($base) ?>/admin/audit_logs.php"><i class="fa-solid fa-shield-halved"></i>Audit Logs</a>
        <?php endif; ?>
        <a href="<?= e($base) ?>/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
    </nav>
</aside>
