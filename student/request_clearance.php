<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/csrf.php';
require_role(['Student']);
$user=current_user();
$st=$pdo->prepare('SELECT s.*,u.name,u.email FROM students s JOIN users u ON u.id=s.user_id WHERE s.user_id=?');$st->execute([$user['id']]);$student=$st->fetch();
$error=$success=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $semester=filter_input(INPUT_POST,'semester',FILTER_VALIDATE_INT);
 if(!$semester||$semester<1||$semester>8){$error='Select a valid semester.';}
 else{
  $existing=$pdo->prepare('SELECT id FROM clearance_requests WHERE student_id=? AND semester=? AND status!="Approved"');
  $existing->execute([$student['id'],$semester]);
  if($existing->fetch()){$error='An active clearance request already exists for this semester.';}
  else{
   try{$pdo->beginTransaction();
    $pdo->prepare('INSERT INTO clearance_requests(student_id,semester,status) VALUES(?,?,\'Pending\')')->execute([$student['id'],$semester]);
    $requestId=(int)$pdo->lastInsertId();
    $departments=$pdo->query('SELECT id FROM departments WHERE active=1 AND mandatory=1')->fetchAll();
    $dc=$pdo->prepare('INSERT INTO department_clearances(request_id,department_id,status) VALUES(?,?,\'Pending\')');
    foreach($departments as $department){$dc->execute([$requestId,(int)$department['id']]);}
    notify($pdo,(int)$user['id'],'Clearance request submitted','Your No Due request for Semester '.$semester.' was submitted successfully.',base_url().'/student/clearance_status.php');
    audit_log($pdo,(int)$user['id'],'Student','Submitted no-due request',$requestId,'Semester '.$semester);
    $pdo->commit();$success='Clearance request submitted successfully.';
   }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error='Unable to submit request. Please try again.';}
  }
 }
}
$pageTitle='Submit Clearance | CLEARX';require_once __DIR__.'/../includes/header.php';
?><div class="app-layout"><?php require_once __DIR__.'/../includes/sidebar.php';?><main class="app-main"><header class="topbar"><button class="menu-toggle"><i class="fa-solid fa-bars"></i></button><div class="topbar-user"><b><?=e($user['name'])?></b><span>Student</span></div><div class="avatar"><?=e(strtoupper(substr($user['name'],0,2)))?></div></header><div class="content"><div class="page-header"><div><p>CLEARANCE REQUEST</p><h1>Submit a New Request</h1></div></div><section class="panel form-panel"><?php if($error):?><div class="alert alert-error"><?=e($error)?></div><?php endif;if($success):?><div class="alert alert-success"><?=e($success)?></div><?php endif;?>
<form method="post" class="auth-form" style="width:100%"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><div class="form-grid"><label>Student Name<input value="<?=e($student['name'])?>" disabled></label><label>USN<input value="<?=e($student['usn'])?>" disabled></label></div><label>Semester<select name="semester" required><option value="">Select semester</option><?php for($i=1;$i<=8;$i++):?><option value="<?=$i?>" <?=$i==(int)$student['semester']?'selected':''?>>Semester <?=$i?></option><?php endfor;?></select></label><div class="notice">Submitting creates pending clearance entries for every active mandatory department.</div><button class="btn btn-primary auth-submit" type="submit">Submit Clearance Request</button></form></section></div><?php require_once __DIR__.'/../includes/footer.php';?></main></div>
