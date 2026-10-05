<?php

declare(strict_types=1);
ob_start();
require __DIR__ . '/config.php';

// NOTE: no custom session_name — all pages share default PHPSESSID
session_set_cookie_params([
 'lifetime' => 0,
 'path'     => '/',
 'httponly' => true,
 'samesite' => 'Lax',
 'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

while (ob_get_level() > 0) ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';

function ok(array $d = []): never
{
 while (ob_get_level() > 0) ob_end_clean();
 http_response_code(200);
 header('Content-Type: application/json; charset=utf-8');
 echo json_encode(['ok' => true] + $d);
 exit;
}

function fail(array $e, int $code = 422): never
{
 while (ob_get_level() > 0) ob_end_clean();
 http_response_code($code);
 header('Content-Type: application/json; charset=utf-8');
 echo json_encode(['ok' => false, 'errors' => $e]);
 exit;
}

function field(string $k): string
{
 return trim((string)($_POST[$k] ?? ''));
}

match ($action) {
 'signup'          => handle_signup(),
 'login'           => handle_login(),
 'lookup_username' => handle_lookup(),
 'reset_password'  => handle_reset(),
 'logout'          => handle_logout(),
 'me'              => handle_me(),
 'update_profile'  => handle_update_profile(),      // ← NEW
 'change_password' => handle_change_password(),     // ← NEW
 default           => fail(['_general' => 'Unknown action.'], 400),
};

// ─────────────────────────────────────────────────────────────
function handle_signup(): never
{
 $username = field('username');
 $first    = field('firstName');
 $middle   = field('middleName');
 $last     = field('lastName');
 $pass     = (string)($_POST['password'] ?? '');
 $confirm  = (string)($_POST['confirmPassword'] ?? '');
 $tos      = !empty($_POST['agreeTerms']);

 $e = [];

 if (!preg_match('/^[a-zA-Z0-9_.]{3,30}$/', $username))
  $e['username'] = 'Username must be 3–30 chars (letters, numbers, _ . only).';

 if ($first === '' || mb_strlen($first) > 80 || !preg_match('/^[\p{L}\s.\'-]+$/u', $first))
  $e['firstName'] = 'Valid first name is required.';

 if ($middle !== '' && mb_strlen($middle) > 80)
  $e['middleName'] = 'Middle name is too long.';

 if ($last === '' || mb_strlen($last) > 80 || !preg_match('/^[\p{L}\s.\'-]+$/u', $last))
  $e['lastName'] = 'Valid last name is required.';

 if (strlen($pass) < 8 || !preg_match('/[A-Za-z]/', $pass) || !preg_match('/\d/', $pass))
  $e['password'] = 'Password needs 8+ chars, with letters and numbers.';

 if ($pass !== $confirm)
  $e['confirmPassword'] = 'Passwords do not match.';

 if (!$tos)
  $e['agreeTerms'] = 'You must agree to the Terms and Privacy Policy.';

 if (!$e) {
  $stmt = db()->prepare('SELECT id FROM attendees WHERE username = ? LIMIT 1');
  $stmt->execute([$username]);
  if ($stmt->fetch()) $e['username'] = 'Username already taken.';
 }

 if ($e) fail($e);

 $uuid = uuid_v4();
 $hash = password_hash($pass, PASSWORD_DEFAULT);

 $stmt = db()->prepare(
  'INSERT INTO attendees (uuid, username, first_name, middle_name, last_name, password_hash)
         VALUES (?,?,?,?,?,?)'
 );
 $stmt->execute([$uuid, $username, $first, $middle, $last, $hash]);

 // NOTE: no auto-login. User must log in explicitly.
 ok(['message' => 'Account created successfully. Please log in.']);
}

// ─────────────────────────────────────────────────────────────
function handle_login(): never
{
 $username = field('username');
 $pass     = (string)($_POST['password'] ?? '');

 if ($username === '' || $pass === '')
  fail(['_general' => 'Enter your username and password.']);

 $stmt = db()->prepare(
  'SELECT id, uuid, username, password_hash FROM attendees WHERE username = ? LIMIT 1'
 );
 $stmt->execute([$username]);
 $row = $stmt->fetch();

 if (!$row || !$row['password_hash'] || !password_verify($pass, $row['password_hash'])) {
  usleep(300000);
  fail(['_general' => 'Invalid username or password.']);
 }

 if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
  $new = password_hash($pass, PASSWORD_DEFAULT);
  db()->prepare('UPDATE attendees SET password_hash = ? WHERE id = ?')
   ->execute([$new, $row['id']]);
 }

 session_regenerate_id(true);
 $_SESSION['attendee_id']   = (int)$row['id'];
 $_SESSION['attendee_uuid'] = $row['uuid'];
 $_SESSION['username']      = $row['username'];

 ok(['redirect' => 'dashboard.php']);
}

// ─────────────────────────────────────────────────────────────
function handle_lookup(): never
{
 $username = field('username');
 if ($username === '') fail(['username' => 'Enter your username.']);

 $stmt = db()->prepare('SELECT id FROM attendees WHERE username = ? LIMIT 1');
 $stmt->execute([$username]);

 if (!$stmt->fetch())
  fail(['username' => 'No account found with that username.']);

 $_SESSION['reset_username'] = $username;
 $_SESSION['reset_issued']   = time();

 ok(['next' => 'reset']);
}

// ─────────────────────────────────────────────────────────────
function handle_reset(): never
{
 $u = $_SESSION['reset_username'] ?? '';
 $t = $_SESSION['reset_issued']   ?? 0;

 if ($u === '' || (time() - $t) > 600)
  fail(['_general' => 'Reset session expired. Please start again.'], 400);

 $pass    = (string)($_POST['password'] ?? '');
 $confirm = (string)($_POST['confirmPassword'] ?? '');
 $e = [];

 if (strlen($pass) < 8 || !preg_match('/[A-Za-z]/', $pass) || !preg_match('/\d/', $pass))
  $e['password'] = 'Password needs 8+ chars, with letters and numbers.';
 if ($pass !== $confirm)
  $e['confirmPassword'] = 'Passwords do not match.';

 if ($e) fail($e);

 $hash = password_hash($pass, PASSWORD_DEFAULT);
 db()->prepare('UPDATE attendees SET password_hash = ? WHERE username = ?')
  ->execute([$hash, $u]);

 unset($_SESSION['reset_username'], $_SESSION['reset_issued']);
 ok(['message' => 'Password updated. You can now log in.']);
}

// ─────────────────────────────────────────────────────────────
function handle_logout(): never
{
 $_SESSION = [];
 if (ini_get('session.use_cookies')) {
  $p = session_get_cookie_params();
  setcookie(
   session_name(),
   '',
   time() - 42000,
   $p['path'],
   $p['domain'],
   $p['secure'],
   $p['httponly']
  );
 }
 session_destroy();
 ok(['redirect' => 'auth.php']);
}

// ─────────────────────────────────────────────────────────────
function handle_me(): never
{
 if (empty($_SESSION['attendee_id'])) fail(['_general' => 'Not logged in.'], 401);

 $stmt = db()->prepare(
  'SELECT uuid, username, first_name, middle_name, last_name, email
         FROM attendees WHERE id = ? LIMIT 1'
 );
 $stmt->execute([$_SESSION['attendee_id']]);
 $row = $stmt->fetch();
 if (!$row) fail(['_general' => 'Account not found.'], 404);

 ok(['user' => $row]);
}

// ─────────────────────────────────────────────────────────────
function handle_update_profile(): never
{
 if (empty($_SESSION['attendee_id'])) fail(['_general' => 'Not logged in.'], 401);

 $id       = (int)$_SESSION['attendee_id'];
 $username = field('username');
 $first    = field('firstName');
 $middle   = field('middleName');
 $last     = field('lastName');

 $e = [];

 if (!preg_match('/^[a-zA-Z0-9_.]{3,30}$/', $username))
  $e['username'] = 'Username must be 3–30 chars (letters, numbers, _ . only).';

 if ($first === '' || mb_strlen($first) > 80 || !preg_match('/^[\p{L}\s.\'-]+$/u', $first))
  $e['firstName'] = 'Valid first name is required.';

 if ($middle !== '' && mb_strlen($middle) > 80)
  $e['middleName'] = 'Middle name is too long.';

 if ($last === '' || mb_strlen($last) > 80 || !preg_match('/^[\p{L}\s.\'-]+$/u', $last))
  $e['lastName'] = 'Valid last name is required.';

 if (!$e) {
  // Uniqueness (exclude self)
  $stmt = db()->prepare('SELECT id FROM attendees WHERE username = ? AND id <> ? LIMIT 1');
  $stmt->execute([$username, $id]);
  if ($stmt->fetch()) $e['username'] = 'Username already taken.';
 }

 if ($e) fail($e);

 db()->prepare(
  'UPDATE attendees SET username = ?, first_name = ?, middle_name = ?, last_name = ?
         WHERE id = ?'
 )->execute([$username, $first, $middle, $last, $id]);

 // Refresh session username
 $_SESSION['username'] = $username;

 ok(['message' => 'Profile updated successfully.']);
}

// ─────────────────────────────────────────────────────────────
function handle_change_password(): never
{
 if (empty($_SESSION['attendee_id'])) fail(['_general' => 'Not logged in.'], 401);

 $id       = (int)$_SESSION['attendee_id'];
 $current  = (string)($_POST['currentPassword'] ?? '');
 $new      = (string)($_POST['newPassword'] ?? '');
 $confirm  = (string)($_POST['confirmPassword'] ?? '');

 $e = [];

 if ($current === '')
  $e['currentPassword'] = 'Enter your current password.';

 if (strlen($new) < 8 || !preg_match('/[A-Za-z]/', $new) || !preg_match('/\d/', $new))
  $e['newPassword'] = 'Password needs 8+ chars, with letters and numbers.';

 if ($new !== $confirm)
  $e['confirmPassword'] = 'Passwords do not match.';

 if ($e) fail($e);

 // Verify current
 $stmt = db()->prepare('SELECT password_hash FROM attendees WHERE id = ? LIMIT 1');
 $stmt->execute([$id]);
 $row = $stmt->fetch();

 if (!$row || !$row['password_hash'] || !password_verify($current, $row['password_hash'])) {
  usleep(300000);
  fail(['currentPassword' => 'Current password is incorrect.']);
 }

 $hash = password_hash($new, PASSWORD_DEFAULT);
 db()->prepare('UPDATE attendees SET password_hash = ? WHERE id = ?')
  ->execute([$hash, $id]);

 ok(['message' => 'Password updated successfully.']);
}
