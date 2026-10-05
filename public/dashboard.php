<?php
session_set_cookie_params([
 'lifetime' => 0,
 'path'     => '/',
 'httponly' => true,
 'samesite' => 'Lax',
 'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

if (empty($_SESSION['attendee_id'])) {
 header('Location: auth.php');
 exit;
}

$username = (string)($_SESSION['username'] ?? '');
$initial  = $username !== '' ? strtoupper($username[0]) : '?';

require __DIR__ . '/api/config.php';

$stmt = db()->prepare(
 'SELECT first_name, last_name, email, qr_token, confirmed_at, signature_path, is_registered
     FROM attendees WHERE id = ? LIMIT 1'
);
$stmt->execute([$_SESSION['attendee_id']]);
$me = $stmt->fetch() ?: [];

// Check evaluation status
$stmt = db()->prepare('SELECT id FROM evaluations WHERE attendee_id = ? LIMIT 1');
$stmt->execute([$_SESSION['attendee_id']]);
$hasEvaluated = (bool)$stmt->fetch();

$hasRegistered = !empty($me['is_registered']);
$isConfirmed   = ($me['status'] ?? 'absent') === 'confirmed' || !empty($me['confirmed_at']);
?>
<!doctype html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Dashboard — 8th RSI Forum</title>
 <link rel="stylesheet" href="./css/output.css" />
 <link rel="preconnect" href="https://fonts.googleapis.com" />
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap" rel="stylesheet" />
 <style>
  .font-inter {
   font-family: "Inter", system-ui, -apple-system, sans-serif;
  }

  .font-serif {
   font-family: "Source Serif 4", Georgia, serif;
  }

  .grid-paper {
   background-image:
    linear-gradient(rgba(0, 91, 123, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 91, 123, 0.05) 1px, transparent 1px);
   background-size: 32px 32px;
  }
 </style>
</head>

<body class="min-h-screen antialiased bg-[#ebf5f8] text-[#0b1214]"
 style="background:
        radial-gradient(900px 500px at 100% 0%, #ffffff 0%, transparent 55%),
        linear-gradient(180deg, #ebf5f8 0%, #dceef4 100%);">

 <?php include __DIR__ . '/includes/header.php'; ?>

 <main class="grid-paper min-h-[calc(100vh-4rem)]">
  <div class="max-w-3xl mx-auto px-4 md:px-8 py-12 md:py-16">

   <div class="mb-10">
    <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-3">
     Attendee Dashboard
    </p>
    <h1 class="font-inter text-3xl md:text-4xl font-light text-[#002735] leading-tight">
     Welcome, <?= htmlspecialchars($me['first_name'] ?: $username) ?>.
    </h1>
    <p class="font-serif text-sm text-[#7c868a] mt-3 max-w-xl">
     Complete your registration, submit your evaluation, and download your certificate — all from here.
    </p>
   </div>

   <div class="flex flex-col gap-4">

    <!-- 1. Registration -->
    <a href="register.php"
     class="group relative block bg-white border border-[#7c868a]/25 rounded-lg overflow-hidden hover:border-[#00adec]/60 hover:shadow-[0_8px_28px_-10px_rgba(0,173,236,0.35)] transition-all duration-300">
     <div class="absolute inset-y-0 left-0 w-1"
      style="background: linear-gradient(180deg, <?= $hasRegistered ? '#00adec' : '#7c868a' ?>, <?= $hasRegistered ? '#005b7b' : '#b6bec1' ?>);"></div>
     <div class="p-6 md:p-7 pl-7 md:pl-8 flex items-start gap-5">
      <div class="shrink-0 w-11 h-11 rounded-full flex items-center justify-center text-white"
       style="background: linear-gradient(135deg, #005b7b, #00adec);">
       <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
        <path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round" />
        <circle cx="12" cy="12" r="9" />
       </svg>
      </div>
      <div class="flex-1 min-w-0">
       <div class="flex items-start justify-between gap-3">
        <h2 class="font-inter text-base md:text-lg font-medium text-[#002735] group-hover:text-[#005b7b] transition-colors">
         Registration Portal
        </h2>
        <span class="shrink-0 font-inter text-[9px] tracking-[0.2em] uppercase px-2 py-1 rounded
                         <?= $hasRegistered
                          ? 'bg-[#00adec]/10 text-[#005b7b]'
                          : 'bg-[#7c868a]/15 text-[#7c868a]' ?>">
         <?= $hasRegistered ? 'Complete' : 'Pending' ?>
        </span>
       </div>
       <p class="font-serif text-sm text-[#7c868a] mt-1.5 leading-relaxed">
        Fill out your attendee information and sign to secure your slot at the 8th RSI Forum.
       </p>
       <span class="inline-flex items-center gap-1.5 mt-3 font-inter text-[10px] tracking-[0.25em] uppercase text-[#005b7b] group-hover:text-[#00adec] transition-colors">
        <?= $hasRegistered ? 'View registration' : 'Start registration' ?>
        <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
         <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
       </span>
      </div>
     </div>
    </a>

    <!-- 2. Evaluation -->
    <a href="evaluation.php"
     class="group relative block bg-white border border-[#7c868a]/25 rounded-lg overflow-hidden hover:border-[#00adec]/60 hover:shadow-[0_8px_28px_-10px_rgba(0,173,236,0.35)] transition-all duration-300 <?= $hasRegistered ? '' : 'opacity-60 pointer-events-none' ?>">
     <div class="absolute inset-y-0 left-0 w-1"
      style="background: linear-gradient(180deg, <?= $hasEvaluated ? '#00adec' : '#7c868a' ?>, <?= $hasEvaluated ? '#005b7b' : '#b6bec1' ?>);"></div>
     <div class="p-6 md:p-7 pl-7 md:pl-8 flex items-start gap-5">
      <div class="shrink-0 w-11 h-11 rounded-full flex items-center justify-center text-white"
       style="background: linear-gradient(135deg, #005b7b, #00adec);">
       <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
        <path d="M9 11l3 3L22 4" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke-linecap="round" />
       </svg>
      </div>
      <div class="flex-1 min-w-0">
       <div class="flex items-start justify-between gap-3">
        <h2 class="font-inter text-base md:text-lg font-medium text-[#002735] group-hover:text-[#005b7b] transition-colors">
         Event Evaluation
        </h2>
        <?php if (!$hasRegistered): ?>
         <span class="shrink-0 font-inter text-[9px] tracking-[0.2em] uppercase px-2 py-1 rounded bg-[#7c868a]/15 text-[#7c868a]">Locked</span>
        <?php elseif ($hasEvaluated): ?>
         <span class="shrink-0 font-inter text-[9px] tracking-[0.2em] uppercase px-2 py-1 rounded bg-[#00adec]/10 text-[#005b7b]">Submitted</span>
        <?php else: ?>
         <span class="shrink-0 font-inter text-[9px] tracking-[0.2em] uppercase px-2 py-1 rounded bg-[#7c868a]/15 text-[#7c868a]">Pending</span>
        <?php endif; ?>
       </div>
       <p class="font-serif text-sm text-[#7c868a] mt-1.5 leading-relaxed">
        <?php if (!$hasRegistered): ?>
         Available after you complete your registration.
        <?php elseif ($hasEvaluated): ?>
         Thanks for your feedback. You can review your answers anytime.
        <?php else: ?>
         Share your feedback after the forum to help us improve future events.
        <?php endif; ?>
       </p>
       <?php if ($hasRegistered): ?>
        <span class="inline-flex items-center gap-1.5 mt-3 font-inter text-[10px] tracking-[0.25em] uppercase text-[#005b7b] group-hover:text-[#00adec] transition-colors">
         <?= $hasEvaluated ? 'Review evaluation' : 'Open evaluation' ?>
         <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
        </span>
       <?php endif; ?>
      </div>
     </div>
    </a>

    <!-- 3. Certificate -->
    <a href="certificate.php"
     class="group relative block bg-white border border-[#7c868a]/25 rounded-lg overflow-hidden hover:border-[#00adec]/60 hover:shadow-[0_8px_28px_-10px_rgba(0,173,236,0.35)] transition-all duration-300 <?= $isConfirmed ? '' : 'opacity-60 pointer-events-none' ?>">
     <div class="absolute inset-y-0 left-0 w-1"
      style="background: linear-gradient(180deg, <?= $isConfirmed ? '#00adec' : '#7c868a' ?>, <?= $isConfirmed ? '#005b7b' : '#b6bec1' ?>);"></div>
     <div class="p-6 md:p-7 pl-7 md:pl-8 flex items-start gap-5">
      <div class="shrink-0 w-11 h-11 rounded-full flex items-center justify-center text-white"
       style="background: linear-gradient(135deg, #005b7b, #00adec);">
       <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
        <circle cx="12" cy="9" r="5" />
        <path d="M9 13.5L7 21l5-2 5 2-2-7.5" stroke-linecap="round" stroke-linejoin="round" />
       </svg>
      </div>
      <div class="flex-1 min-w-0">
       <div class="flex items-start justify-between gap-3">
        <h2 class="font-inter text-base md:text-lg font-medium text-[#002735] group-hover:text-[#005b7b] transition-colors">
         Certificate
        </h2>
        <?php if (!$isConfirmed): ?>
         <span class="shrink-0 font-inter text-[9px] tracking-[0.2em] uppercase px-2 py-1 rounded bg-[#7c868a]/15 text-[#7c868a]">Locked</span>
        <?php else: ?>
         <span class="shrink-0 font-inter text-[9px] tracking-[0.2em] uppercase px-2 py-1 rounded bg-[#00adec]/10 text-[#005b7b]">Ready</span>
        <?php endif; ?>
       </div>
       <p class="font-serif text-sm text-[#7c868a] mt-1.5 leading-relaxed">
        <?= $isConfirmed
         ? 'Download your Certificate of Participation as a print-ready A4 PDF.'
         : 'Unlocks once your attendance is confirmed by event staff.' ?>
       </p>
       <?php if ($isConfirmed): ?>
        <span class="inline-flex items-center gap-1.5 mt-3 font-inter text-[10px] tracking-[0.25em] uppercase text-[#005b7b] group-hover:text-[#00adec] transition-colors">
         Download PDF
         <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 4v12M6 12l6 6 6-6M5 21h14" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
        </span>
       <?php endif; ?>
      </div>
     </div>
    </a>

   </div>

   <p class="font-inter text-[10px] tracking-[0.25em] uppercase text-[#7c868a] text-center mt-12">
    © 2026 CALABARZON RDC · RRDIC
   </p>

  </div>
 </main>
</body>

</html>