<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

function send_qr_email(string $email, string $name, string $token): void
{
 // Ensure QR cache dir exists
 if (!is_dir(QR_DIR)) {
  mkdir(QR_DIR, 0775, true);
 }

 $payload = PUBLIC_BASE . '/api/scan.php?t=' . $token;

 // ── Generate QR ──────────────────────────────────────────
 try {
  $qr = new QrCode(
   data: $payload,
   encoding: new Encoding('UTF-8'),
   errorCorrectionLevel: ErrorCorrectionLevel::Medium,
   size: 800,
   margin: 8,
  );

  $writer = new PngWriter();
  $result = $writer->write($qr);

  $qrFile = QR_DIR . '/' . $token . '.png';
  $result->saveToFile($qrFile);
 } catch (Throwable $e) {
  error_log('QR generation failed: ' . $e->getMessage());
  return;
 }

 // ── Resolve logo path ────────────────────────────────────
 // Look for the RDC logo next to the public/assets folder.
 $logoCandidates = [
  __DIR__ . '/../assets/icons/rdc-calabarzon-logo-nobg.png',
  __DIR__ . '/../../public/assets/icons/rdc-calabarzon-logo-nobg.png',
  __DIR__ . '/../assets/icons/rdc-calabarzon-logo.png',
 ];
 $logoFile = null;
 foreach ($logoCandidates as $c) {
  if (is_file($c)) {
   $logoFile = $c;
   break;
  }
 }

 // ── Send mail ────────────────────────────────────────────
 $mail = new PHPMailer(true);
 $mail->Timeout        = 8;
 $mail->SMTPDebug      = 0;
 $mail->SMTPKeepAlive  = false;

 try {
  $mail->isSMTP();
  $mail->Host       = SMTP_HOST;
  $mail->SMTPAuth   = true;
  $mail->Username   = SMTP_USER;
  $mail->Password   = SMTP_PASS;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = SMTP_PORT;

  $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
  $mail->addAddress($email, $name);

  $mail->isHTML(true);
  $mail->Subject = 'Your Attendee ID & QR Pass — 8th RSI Forum';
  $mail->Body    = qr_email_html($name, $token);
  $mail->AltBody = qr_email_plain($name, $token);

  // Embed QR
  $mail->addEmbeddedImage($qrFile, 'qrcode', 'qr-pass.png');

  // Embed logo if available
  if ($logoFile !== null) {
   $mail->addEmbeddedImage($logoFile, 'rdclogo', basename($logoFile));
  }

  $mail->send();
 } catch (MailException $e) {
  error_log('Mail send failed for ' . $email . ': ' . $mail->ErrorInfo);
 } catch (Throwable $e) {
  error_log('Mail send fatal for ' . $email . ': ' . $e->getMessage());
 }
}

// ─────────────────────────────────────────────────────────────
function qr_email_html(string $name, string $token): string
{
 $safeName  = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
 $safeToken = htmlspecialchars(strtoupper(substr($token, 0, 12)), ENT_QUOTES, 'UTF-8');

 return <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Attendee ID — 8th RSI Forum</title>
</head>
<body style="margin:0;padding:0;background:#ebf5f8;font-family:'Source Serif 4',Georgia,'Times New Roman',serif;color:#0b1214;-webkit-font-smoothing:antialiased;">

  <!-- Wrapper -->
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#ebf5f8;padding:40px 16px;">
    <tr>
      <td align="center">

        <!-- Card -->
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
               style="max-width:600px;width:100%;background:#ffffff;border:1px solid rgba(124,134,138,0.25);border-radius:10px;overflow:hidden;box-shadow:0 12px 40px -20px rgba(0,39,53,0.35);">

          <!-- ═══ HEADER BAND ═══ -->
          <tr>
            <td style="background:linear-gradient(135deg,#002735 0%,#003a4d 55%,#005b7b 100%);padding:28px 32px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td valign="middle" style="width:56px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                           style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.18);border-radius:10px;">
                      <tr>
                        <td style="padding:8px;text-align:center;vertical-align:middle;">
                          <img src="cid:rdclogo" alt="RDC CALABARZON" width="36" height="36"
                               style="display:block;border:0;outline:none;text-decoration:none;">
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td valign="middle" style="padding-left:16px;">
                    <p style="margin:0 0 4px;font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:0.28em;text-transform:uppercase;color:#00adec;">
                      CALABARZON · RDC · RRDIC
                    </p>
                    <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:500;letter-spacing:0.02em;color:#ebf5f8;">
                      8th Research, Statistics &amp; Innovation Forum
                    </p>
                  </td>
                  <td valign="middle" align="right" style="width:110px;">
                    <span style="display:inline-block;font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.25em;text-transform:uppercase;color:#002735;background:#00adec;padding:6px 10px;border-radius:999px;font-weight:600;">
                      Attendee ID
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- ═══ BODY ═══ -->
          <tr>
            <td style="padding:36px 32px 8px;">
              <p style="margin:0 0 6px;font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:0.3em;text-transform:uppercase;color:#00adec;">
                Official Pass
              </p>
              <h1 style="margin:0 0 20px;font-family:Arial,Helvetica,sans-serif;font-weight:300;font-size:26px;line-height:1.2;color:#002735;">
                Hello, {$safeName}
              </h1>
              <p style="margin:0 0 8px;font-size:15px;line-height:1.65;color:#35393a;">
                Your registration for the <strong style="color:#002735;">8th Research, Statistics, and Innovation Forum</strong> has been confirmed.
                This email serves as your official attendee ID — please present the QR code below at the entrance on event day.
              </p>
            </td>
          </tr>

          <!-- ═══ ID CARD CORE ═══ -->
          <tr>
            <td style="padding:16px 32px 32px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:linear-gradient(160deg,#002735 0%,#003a4d 60%,#005b7b 100%);border-radius:14px;overflow:hidden;">

                <!-- Cyan strip -->
                <tr>
                  <td style="height:4px;background:linear-gradient(90deg,#005b7b,#00adec);"></td>
                </tr>

                <!-- Content -->
                <tr>
                  <td style="padding:28px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <!-- Left: QR -->
                        <td width="200" valign="top" align="center" style="padding-right:22px;">
                          <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                                 style="background:#ffffff;border-radius:10px;padding:10px;">
                            <tr>
                              <td align="center" valign="middle">
                                <img src="cid:qrcode" alt="QR Pass" width="160" height="160"
                                     style="display:block;border:0;outline:none;text-decoration:none;">
                              </td>
                            </tr>
                          </table>
                          <p style="margin:10px 0 0;font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.28em;text-transform:uppercase;color:#7fd4f5;text-align:center;">
                            Scan at entrance
                          </p>
                        </td>

                        <!-- Right: details -->
                        <td valign="top">
                          <p style="margin:0 0 4px;font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.28em;text-transform:uppercase;color:#00adec;">
                            Attendee
                          </p>
                          <p style="margin:0 0 18px;font-family:Arial,Helvetica,sans-serif;font-size:17px;font-weight:500;color:#ebf5f8;letter-spacing:0.01em;">
                            {$safeName}
                          </p>

                          <p style="margin:0 0 4px;font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.28em;text-transform:uppercase;color:#00adec;">
                            Reference No.
                          </p>
                          <p style="margin:0 0 18px;font-family:'Courier New',monospace;font-size:13px;color:#ebf5f8;letter-spacing:0.08em;">
                            {$safeToken}
                          </p>

                          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                 style="border-top:1px solid rgba(0,173,236,0.35);padding-top:14px;">
                            <tr>
                              <td style="padding-bottom:6px;">
                                <span style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.28em;text-transform:uppercase;color:#00adec;">Date</span><br>
                                <span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#ebf5f8;">Oct 21, 2026</span>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <span style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.28em;text-transform:uppercase;color:#00adec;">Venue</span><br>
                                <span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#ebf5f8;">Batangas State University<br>Alangilan, Batangas City</span>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <!-- Footer strip inside the ID -->
                <tr>
                  <td style="background:rgba(0,0,0,0.28);padding:12px 28px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.24em;text-transform:uppercase;color:#7fd4f5;">
                          In-Person · 08:00 AM – 05:00 PM
                        </td>
                        <td align="right" style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:0.24em;text-transform:uppercase;color:#7fd4f5;">
                          Official Attendee Pass
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- ═══ INSTRUCTIONS ═══ -->
          <tr>
            <td style="padding:0 32px 8px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                     style="background:#ebf5f8;border-left:3px solid #00adec;border-radius:6px;">
                <tr>
                  <td style="padding:16px 20px;">
                    <p style="margin:0 0 6px;font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:0.25em;text-transform:uppercase;color:#005b7b;">
                      Reminders
                    </p>
                    <p style="margin:0;font-size:13px;line-height:1.6;color:#35393a;">
                      • This QR code is unique to you — please do not share or forward it.<br>
                      • Screenshots are accepted. Have the code ready at the entrance.<br>
                      • Bring a valid ID matching the name on this pass.
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- ═══ SIGN OFF ═══ -->
          <tr>
            <td style="padding:24px 32px 32px;">
              <p style="margin:0 0 8px;font-size:14px;line-height:1.6;color:#35393a;">
                We look forward to seeing you at the forum.
              </p>
              <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;color:#002735;">
                — Regional Development Council, CALABARZON
              </p>
            </td>
          </tr>

          <!-- ═══ FOOTER ═══ -->
          <tr>
            <td style="background:#f4f9fb;border-top:1px solid rgba(124,134,138,0.18);padding:20px 32px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#7c868a;">
                    © 2026 CALABARZON RDC · RRDIC
                  </td>
                  <td align="right" style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#7c868a;">
                    Official Event Communication
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
        <!-- /Card -->

        <!-- Outer caption -->
        <p style="max-width:600px;margin:16px auto 0;font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:0.2em;text-transform:uppercase;color:#7c868a;text-align:center;">
          This is an automated message. Please do not reply.
        </p>

      </td>
    </tr>
  </table>

</body>
</html>
HTML;
}

// ─────────────────────────────────────────────────────────────
function qr_email_plain(string $name, string $token): string
{
 $short = strtoupper(substr($token, 0, 12));
 return "Hello {$name},\n\n"
  . "Your registration for the 8th Research, Statistics, and Innovation Forum is confirmed.\n"
  . "Please view the HTML version of this email for your official QR pass.\n\n"
  . "Reference No.: {$short}\n"
  . "Date:    October 21, 2026, 08:00 AM – 05:00 PM\n"
  . "Venue:   Batangas State University, Alangilan, Batangas City\n"
  . "Format:  In-Person\n\n"
  . "Do not share your QR code.\n\n"
  . "— Regional Development Council, CALABARZON\n";
}
