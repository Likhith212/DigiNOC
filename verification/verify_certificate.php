<?php
require_once __DIR__.'/../config/database.php';
$certificateId=trim($_GET['id']??'');
$certificate=null;
if($certificateId!==''){
$st=$pdo->prepare('SELECT c.*,s.usn,s.semester,u.name,d.name department_name FROM certificates c JOIN students s ON s.id=c.student_id JOIN users u ON u.id=s.user_id LEFT JOIN departments d ON d.id=s.department_id WHERE c.certificate_id=?');
$st->execute([$certificateId]);$certificate=$st->fetch();
}
?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width"><title>Verify Certificate | CLEARX</title><link rel="stylesheet" href="../assets/css/style.css"></head><body style="min-height:100vh;display:grid;place-items:center;background:#07111f"><main class="card" style="width:min(520px,90%);padding:32px;text-align:center"><div class="brand" style="margin-bottom:25px"><span>CLEAR</span>X</div><h1>Certificate Verification</h1><?php if($certificate&&!$certificate['revoked']):?><span class="verification-status"><i class="fa-solid fa-circle-check"></i> Verified</span><div style="text-align:left;margin-top:24px"><p><b>Certificate ID:</b> <?=e($certificate['certificate_id'])?></p><p><b>Student Name:</b> <?=e($certificate['name'])?></p><p><b>USN:</b> <?=e($certificate['usn'])?></p><p><b>Department:</b> <?=e($certificate['department_name'])?></p><p><b>Semester:</b> <?=e($certificate['semester'])?></p><p><b>Issue Date:</b> <?=e(date('d F Y',strtotime($certificate['issue_date'])))?></p></div><?php else: ?><span class="status rejected">Invalid or revoked certificate</span><?php endif;?></main></body></html>
