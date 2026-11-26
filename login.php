<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

if (is_logged_in()) {
    $map = ['Student'=>'student','Faculty'=>'faculty','HOD'=>'hod','Principal'=>'principal','Super Admin'=>'admin'];
    redirect(base_url() . '/' . $map[current_user()['role']] . '/dashboard.php');
}

$error = flash('error');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($identifier === '' || $password === '') {
        $error = 'Enter your email, USN or employee ID and password.';
    } else {
        $statement = $pdo->prepare('SELECT * FROM users WHERE identifier=? OR email=? LIMIT 1');
        $statement->execute([$identifier, $identifier]);
        $user = $statement->fetch();
        $locked = $user && $user['locked_until'] && strtotime($user['locked_until']) > time();
        if (!$user || $locked || !password_verify($password, $user['password_hash'])) {
            if ($user) {
                $failed = (int)$user['failed_attempts'] + 1;
                $lockUntil = $failed >= 5 ? date('Y-m-d H:i:s', time() + 900) : null;
                $pdo->prepare('UPDATE users SET failed_attempts=?, locked_until=? WHERE id=?')->execute([$failed, $lockUntil, $user['id']]);
            }
            $error = $locked ? 'Your account is temporarily locked. Try again later.' : 'Invalid login credentials.';
        } elseif ($user['status'] !== 'Active') {
            $error = 'This account is inactive or locked.';
        } else {
            $pdo->prepare('UPDATE users SET failed_attempts=0, locked_until=NULL WHERE id=?')->execute([$user['id']]);
            session_regenerate_id(true);
            $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'identifier'=>$user['identifier'],'role'=>$user['role']];
            audit_log($pdo, (int)$user['id'], $user['role'], 'User login');
            $map = ['Student'=>'student','Faculty'=>'faculty','HOD'=>'hod','Principal'=>'principal','Super Admin'=>'admin'];
            redirect(base_url() . '/' . $map[$user['role']] . '/dashboard.php');
        }
    }
}
$pageTitle = 'Login | CLEARX';
require_once __DIR__ . '/includes/header.php';
?>
<main class="auth-shell">
  <section class="auth-brand"><a class="brand" href="index.php"><span>CLEAR</span>X</a><div><h2>Smart clearance.<br><em>Zero hassle.</em></h2><p>One secure platform for every approval stage.</p></div></section>
  <section class="auth-panel">
    <form class="auth-form" method="post">
      <div class="auth-heading"><span>WELCOME BACK</span><h1>Sign in to CLEARX</h1></div>
      <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <label>Email / USN / Employee ID<input type="text" name="identifier" autocomplete="username" required autofocus></label>
      <label>Password<div class="password-field"><input type="password" name="password" autocomplete="current-password" required><button type="button" class="password-toggle" aria-label="Show password"><i class="fa-solid fa-eye"></i></button></div></label>
      <button class="btn btn-primary auth-submit" type="submit">Sign In <i class="fa-solid fa-arrow-right"></i></button>
      <p class="auth-switch">New student? <a href="register.php">Create an account</a></p>
    </form>
  </section>
</main>
<script src="assets/js/script.js"></script>
</body></html>
