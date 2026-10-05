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
 json_out(['ok' => false, 'error' => 'Method not allowed'], 405);
}

if (empty($_SESSION['attendee_id'])) {
 json_out(['ok' => false, 'error' => 'Not authenticated.'], 401);
}

$attendeeId = (int)$_SESSION['attendee_id'];

$in = json_in();

// ── Whitelists ────────────────────────────────────────────────
$GENDERS         = ['male', 'female', 'other'];
$CLASSIFICATIONS = ['business', 'government', 'homemaker', 'media', 'others', 'private', 'student'];
$VISITOR_TYPES   = ['exhibitor', 'organizer', 'participant', 'speaker', 'volunteer'];
$REGION_IDS      = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17'];

$errors = [];

function field(array $in, string $key): string
{
 return trim((string)($in[$key] ?? ''));
}

// ── Validation ────────────────────────────────────────────────
if (empty($in['privacyConsent'])) {
 $errors['privacyConsent'] = 'You must agree to the data privacy consent.';
}

$firstName  = field($in, 'firstName');
$middleName = field($in, 'middleName');
$lastName   = field($in, 'lastName');

if ($firstName === '') {
 $errors['firstName'] = 'First name is required.';
} elseif (mb_strlen($firstName) > 80 || !preg_match('/^[\p{L}\s.\'-]+$/u', $firstName)) {
 $errors['firstName'] = 'First name is invalid.';
}

if (mb_strlen($middleName) > 80) {
 $errors['middleName'] = 'Middle name is too long.';
}

if ($lastName === '') {
 $errors['lastName'] = 'Last name is required.';
} elseif (mb_strlen($lastName) > 80 || !preg_match('/^[\p{L}\s.\'-]+$/u', $lastName)) {
 $errors['lastName'] = 'Last name is invalid.';
}

$gender = field($in, 'gender');
if (!in_array($gender, $GENDERS, true)) {
 $errors['gender'] = 'Invalid gender selection.';
}

$ageRange = field($in, 'ageRange');
if ($ageRange === '') {
 $errors['ageRange'] = 'Age is required.';
} elseif (!ctype_digit($ageRange)) {
 $errors['ageRange'] = 'Age must be a whole number.';
} else {
 $ageInt = (int)$ageRange;
 if ($ageInt < 1 || $ageInt > 120) {
  $errors['ageRange'] = 'Age must be between 1 and 120.';
 } else {
  $ageRange = (string)$ageInt;
 }
}

$classification = field($in, 'classification');
if (!in_array($classification, $CLASSIFICATIONS, true)) {
 $errors['classification'] = 'Invalid classification.';
}

$email = field($in, 'contactEmail');
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 160) {
 $errors['contactEmail'] = 'Invalid email.';
}

$visitorType = field($in, 'visitorType');
if (!in_array($visitorType, $VISITOR_TYPES, true)) {
 $errors['visitorType'] = 'Invalid visitor type.';
}

$affiliation = field($in, 'affiliation');
if ($affiliation === '' || mb_strlen($affiliation) > 160) {
 $errors['affiliation'] = 'Invalid affiliation.';
}

$region = field($in, 'region');
if (!in_array($region, $REGION_IDS, true)) {
 $errors['region'] = 'Invalid region.';
}

// ── Signature validation ──────────────────────────────────────
$signature    = (string)($in['signature'] ?? '');
$signatureBin = null;

if ($signature === '') {
 $errors['signature'] = 'Signature is required.';
} elseif (!preg_match('#^data:image/png;base64,#i', $signature)) {
 $errors['signature'] = 'Signature format is invalid.';
} else {
 $b64          = substr($signature, strpos($signature, ',') + 1);
 $signatureBin = base64_decode($b64, true);
 if ($signatureBin === false || strlen($signatureBin) < 500) {
  $errors['signature'] = 'Signature appears empty.';
 } elseif (strlen($signatureBin) > 2 * 1024 * 1024) {
  $errors['signature'] = 'Signature is too large (max 2 MB).';
 }
}

// ── Duplicate email (exclude self) ────────────────────────────
if (!isset($errors['contactEmail']) && $email !== '') {
 try {
  $stmt = db()->prepare('SELECT id FROM attendees WHERE email = ? AND id <> ? LIMIT 1');
  $stmt->execute([$email, $attendeeId]);
  if ($stmt->fetch()) {
   $errors['contactEmail'] = 'This email is already registered.';
  }
 } catch (Throwable $e) {
  error_log('DB error in register.php: ' . $e->getMessage());
  json_out(['ok' => false, 'error' => 'Server error.'], 500);
 }
}

if (!empty($errors)) {
 json_out(['ok' => false, 'errors' => $errors], 422);
}

// ── Get the attendee's existing uuid ──────────────────────────
$uuid = (string)($_SESSION['attendee_uuid'] ?? '');
if ($uuid === '') {
 $stmt = db()->prepare('SELECT uuid FROM attendees WHERE id = ? LIMIT 1');
 $stmt->execute([$attendeeId]);
 $uuid = (string)($stmt->fetchColumn() ?: '');
}
if ($uuid === '') {
 json_out(['ok' => false, 'error' => 'Account not found.'], 404);
}

$token = token_hex(32);

// ── Save signature PNG ────────────────────────────────────────
$sigDir = SIGNATURE_DIR;
if (!is_dir($sigDir)) {
 mkdir($sigDir, 0775, true);
}
$sigFile = $sigDir . '/' . $uuid . '.png';
if (file_put_contents($sigFile, $signatureBin) === false) {
 json_out(['ok' => false, 'error' => 'Could not save signature.'], 500);
}
$sigRelPath = 'signatures/' . $uuid . '.png';

// ── UPDATE the logged-in attendee's row (do NOT insert) ───────
try {
 $stmt = db()->prepare(
  'UPDATE attendees SET
            qr_token       = ?,
            first_name     = ?,
            middle_name    = ?,
            last_name      = ?,
            gender         = ?,
            age_range      = ?,
            classification = ?,
            email          = ?,
            visitor_type   = ?,
            affiliation    = ?,
            region         = ?,
            signature_path = ?,
            is_registered  = 1
         WHERE id = ?'
 );
 $stmt->execute([
  $token,
  $firstName,
  $middleName,
  $lastName,
  $gender,
  $ageRange,
  $classification,
  $email,
  $visitorType,
  $affiliation,
  $region,
  $sigRelPath,
  $attendeeId,
 ]);
} catch (Throwable $e) {
 error_log('Registration update failed: ' . $e->getMessage());
 if (is_file($sigFile)) @unlink($sigFile);
 json_out(['ok' => false, 'error' => 'Could not save registration.'], 500);
}

// ── Respond (JSON) ────────────────────────────────────────────
$response = json_encode([
 'ok'      => true,
 'uuid'    => $uuid,
 'message' => 'Registration complete. A QR pass will be sent to your email.',
]);

http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
header('Content-Length: ' . strlen($response));
header('Connection: close');

while (ob_get_level() > 0) ob_end_clean();
echo $response;

if (function_exists('fastcgi_finish_request')) {
 fastcgi_finish_request();
} else {
 flush();
}

// ── Background: email the QR ──────────────────────────────────
ignore_user_abort(true);
set_time_limit(15);

require_once __DIR__ . '/send_qr.php';
send_qr_email($email, $firstName . ' ' . $lastName, $token);
exit;
