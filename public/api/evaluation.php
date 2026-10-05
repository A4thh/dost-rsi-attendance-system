<?php

declare(strict_types=1);
ob_start();
require __DIR__ . '/config.php';

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 http_response_code(405);
 echo json_encode(['ok' => false, 'errors' => ['_general' => 'Method not allowed.']]);
 exit;
}

if (empty($_SESSION['attendee_id'])) {
 http_response_code(401);
 echo json_encode(['ok' => false, 'errors' => ['_general' => 'Not authenticated.']]);
 exit;
}

$action = $_POST['action'] ?? '';
if ($action !== 'submit_evaluation') {
 http_response_code(400);
 echo json_encode(['ok' => false, 'errors' => ['_general' => 'Unknown action.']]);
 exit;
}

$attendeeId = (int)$_SESSION['attendee_id'];
$errors = [];

// Numeric 1–5 answers
$answers = [];
foreach (
 [
  'q1Overall'   => 'q1_overall',
  'q2Content'   => 'q2_content',
  'q3Speakers'  => 'q3_speakers',
  'q4Venue'     => 'q4_venue',
  'q5Recommend' => 'q5_recommend',
 ] as $field => $col
) {
 $v = trim((string)($_POST[$field] ?? ''));
 if ($v === '') {
  $errors[$field] = 'Please select an answer.';
 } elseif (!ctype_digit($v) || (int)$v < 1 || (int)$v > 5) {
  $errors[$field] = 'Invalid value.';
 } else {
  $answers[$col] = (int)$v;
 }
}

$comments = trim((string)($_POST['comments'] ?? ''));
if (mb_strlen($comments) > 2000) {
 $errors['comments'] = 'Comments are too long (max 2000 characters).';
}

// Must have completed registration
$stmt = db()->prepare('SELECT is_registered FROM attendees WHERE id = ? LIMIT 1');
$stmt->execute([$attendeeId]);
$att = $stmt->fetch();
if (!$att || empty($att['is_registered'])) {
 $errors['_general'] = 'Complete your registration before submitting an evaluation.';
}

// Prevent duplicate
if (!$errors) {
 $stmt = db()->prepare('SELECT id FROM evaluations WHERE attendee_id = ? LIMIT 1');
 $stmt->execute([$attendeeId]);
 if ($stmt->fetch()) {
  $errors['_general'] = 'You already submitted an evaluation.';
 }
}

if ($errors) {
 http_response_code(422);
 echo json_encode(['ok' => false, 'errors' => $errors]);
 exit;
}

try {
 $stmt = db()->prepare(
  'INSERT INTO evaluations
            (attendee_id, q1_overall, q2_content, q3_speakers, q4_venue, q5_recommend, comments)
         VALUES (?,?,?,?,?,?,?)'
 );
 $stmt->execute([
  $attendeeId,
  $answers['q1_overall'],
  $answers['q2_content'],
  $answers['q3_speakers'],
  $answers['q4_venue'],
  $answers['q5_recommend'],
  $comments !== '' ? $comments : null,
 ]);
} catch (Throwable $e) {
 error_log('Evaluation insert failed: ' . $e->getMessage());
 http_response_code(500);
 echo json_encode(['ok' => false, 'errors' => ['_general' => 'Could not save evaluation.']]);
 exit;
}

http_response_code(200);
echo json_encode(['ok' => true, 'message' => 'Evaluation submitted. Thank you!']);
