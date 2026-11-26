<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/csrf.php';
require_role(['Student']);
$user=current_user();
$student=$pdo->prepare('SELECT s.*,d.name department_name,u.name,u.email,u.identifier FROM students s JOIN users u ON u.id=s.user_id LEFT JOIN departments d ON d.id=s.department_id WHERE s.user_id=?');
$student->execute([$user['id']]); $student=$student->fetch();
$counts=$pdo->prepare('SELECT
 (SELECT COUNT(*) FROM department_clearances dc JOIN clearance_requests cr ON cr.id=dc.request_id WHERE cr.student_id=? AND dc.status="Approved") approved,
 (SELECT COUNT(*) FROM department_clearances dc JOIN clearance_requests cr ON cr.id=dc.request_id WHERE cr.student_id=? AND dc.status="Pending") pending,
 (SELECT COUNT(*) FROM department_clearances dc JOIN clearance_requests cr ON cr.id=dc.request_id WHERE cr.student_id=? AND dc.status="Rejected") rejected');
$counts->execute([$student['id'],$student['id'],$student['id']]); $counts=$counts->fetch();
$requests=$pdo->prepare('SELECT * FROM clearance_requests WHERE student_id=? ORDER BY submitted_at DESC LIMIT 5');
$requests->execute([$student['id']]); $requests=$requests->fetchAll();
$active=$pdo->prepare('SELECT * FROM clearance_requests WHERE student_id=? AND status!="Approved" ORDER BY submitted_at DESC LIMIT 1');
$active->execute([$student['id']]); $active=$active->fetch();
$progress=0;
if($active){$tot=$pdo->prepare('SELECT COUNT(*) FROM department_clearances WHERE request_id=?');$tot->execute([$active['id']]);$total=(int)$tot->fetchColumn();$app=$pdo->prepare('SELECT COUNT(*) FROM department_clearances WHERE request_id=? AND status="Approved"');$app->execute([$active['id']]);$progress=$total?round($app->fetchColumn()*100/$total):0;}
$pageTitle='Student Dashboard | CLEARX'; require_once __DIR__.'/../includes/header.php';
?><div class="app-layout"><?php require_once __DIR__.'/../includes/sidebar.php'; ?><main class="app-main">
<header class="topbar"><button class="menu-toggle"><i class="fa-solid fa-bars"></i></button><div class="topbar-user"><b><?=e($user['name'])?></b><span>Student</span></div><div class="avatar"><?=e(strtoupper(substr($user['name'],0,2)))?></div></header>
<div class="content"><div class="page-header"><div><p>STUDENT WORKSPACE</p><h1>Good <?=date('H')<12?'morning':(date('H')<18?'afternoon':'evening')?>, <?=e(explode(' ',$user['name'])[0])?></h1></div><a class="btn btn-primary" href="request_clearance.php"><i class="fa-solid fa-plus"></i> New Request</a></div>
<div class="cards">
<div class="card metric"><div class="metric-head"><span>Total Departments</span><div class="metric-icon"><i class="fa-solid fa-building"></i></div></div><h2><?=e($pdo->query('SELECT COUNT(*) FROM departments WHERE active=1')->fetchColumn())?></h2><p>Mandatory clearance units</p></div>
<div class="card metric"><div class="metric-head"><span>Approved</span><div class="metric-icon"><i class="fa-solid fa-circle-check"></i></div></div><h2><?= (int)$counts['approved'] ?></h2><p>Department approvals</p></div>
<div class="card metric"><div class="metric-head"><span>Pending</span><div class="metric-icon"><i class="fa-solid fa-clock"></i></div></div><h2><?= (int)$counts['pending'] ?></h2><p>Awaiting verification</p></div>
<div class="card metric"><div class="metric-head"><span>Rejected</span><div class="metric-icon"><i class="fa-solid fa-circle-xmark"></i></div></div><h2><?= (int)$counts['rejected'] ?></h2><p>Requiring attention</p></div>
</div>
<div class="two-col"><section class="panel"><div class="panel-header"><h2>Recent Clearance Requests</h2><a class="action-btn" href="clearance_status.php">View all</a></div><div class="table-wrap"><table class="data-table"><thead><tr><th>Semester</th><th>Submitted</th><th>Status</th></tr></thead><tbody><?php foreach($requests as $r):?><tr><td>Semester <?=e($r['semester'])?></td><td><?=date('d M Y',strtotime($r['submitted_at']))?></td><td><b class="status <?=strtolower(str_replace(' ','-',e($r['status'])))?>"><?=e($r['status'])?></b></td></tr><?php endforeach;if(!$requests):?><tr><td colspan="3">No clearance requests yet.</td></tr><?php endif;?></tbody></table></div></section>
<section class="panel"><div class="panel-header"><h2>Clearance Progress</h2><b><?= $progress ?>%</b></div><div class="progress-row"><div class="progress-bar"><span style="width:<?= $progress ?>%"></span></div></div><p style="color:var(--muted);font-size:12px;margin-top:18px"><?= $active?'Your active request is in progress.':'No active request. Submit a request to begin.' ?></p></section></div>
</div><?php require_once __DIR__.'/../includes/footer.php';?></main></div>
