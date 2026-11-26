<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $usn = strtoupper(trim($_POST['usn'] ?? ''));
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $semester = filter_input(INPUT_POST, 'semester', FILTER_VALIDATE_INT);
    $departmentId = filter_input(INPUT_POST, 'department_id', FILTER_VALIDATE_INT);
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $name === '' || $usn === '' || !$semester || !$departmentId) {
        $error = 'Complete all fields and use a password with at least 8 characters.';
    } else {
        try {
            $pdo->beginTransaction();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare('INSERT INTO users (name, identifier, email, password_hash, role) VALUES (?, ?, ?, ?, \'Student\')')
                ->execute([$name, $usn, $email, $hash]);
            $userId = (int)$pdo->lastInsertId();
            $pdo->prepare('INSERT INTO students (user_id, usn, department_id, semester, phone) VALUES (?, ?, ?, ?, ?)')
                ->execute([$userId, $usn, $departmentId, $semester, $phone]);
            $pdo->commit();
            flash('error', 'Account created. Sign in to continue.');
            redirect(base_url() . '/login.php');
        } catch (PDOException $exception) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'USN or email is already registered.';
        }
    }
}
$departments = $pdo->query('SELECT id, name FROM departments WHERE active=1 ORDER BY name')->fetchAll();
$pageTitle = 'Create Account | CLEARX';
require_once __DIR__ . '/includes/header.php';
?>
<main class="auth-shell">
  <section class="auth-brand"><a class="brand" href="index.php"><span>CLEAR</span>X</a><div><h2>Join the digital<br><em>clearance revolution.</em></h2></div></section>
  <section class="auth-panel">
    <form class="auth-form register-form" method="post">
      <div class="auth-heading"><span>STUDENT REGISTRATION</span><h1>Create your account</h1></div>
      <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <label>Full name<input type="text" name="name" required></label>
      <div class="form-grid"><label>USN<input type="text" name="usn" required></label><label>Phone<input type="tel" name="phone"></label></div>
      <label>Email<input type="email" name="email" required></label>
      <div class="form-grid"><label>Department<select name="department_id" required><option value="">Select</option><?php foreach ($departments as $department): ?><option value="<?= (int)$department['id'] ?>"><?= e($department['name']) ?></option><?php endforeach; ?></select></label><label>Semester<select name="semester" required><?php for($i=1;$i<=8;$i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?></select></label></div>
      <label>Password<input type="password" name="password" minlength="8" required></label>
      <button class="btn btn-primary auth-submit" type="submit">Create Account</button>
      <p class="auth-switch">Already registered? <a href="login.php">Sign in</a></p>
    </form>
  </section>
</main>
<script src="assets/js/script.js"></script>
</body></html>
