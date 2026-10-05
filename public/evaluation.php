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

require __DIR__ . '/api/config.php';

// Must have completed registration first
$stmt = db()->prepare(
 'SELECT first_name, last_name, qr_token, signature_path, is_registered
     FROM attendees WHERE id = ? LIMIT 1'
);
$stmt->execute([$_SESSION['attendee_id']]);
$me = $stmt->fetch() ?: [];

$hasRegistered = !empty($me['is_registered']);
if (!$hasRegistered) {
 header('Location: dashboard.php');
 exit;
}

// Existing evaluation?
$stmt = db()->prepare(
 'SELECT q1_overall, q2_content, q3_speakers, q4_venue, q5_recommend, comments, submitted_at
     FROM evaluations WHERE attendee_id = ? LIMIT 1'
);
$stmt->execute([$_SESSION['attendee_id']]);
$existing = $stmt->fetch() ?: null;
$alreadyDone = (bool)$existing;
?>
<!doctype html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Evaluation — 8th RSI Forum</title>
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

  .error-msg {
   font-family: "Inter", sans-serif;
   font-size: 11px;
   letter-spacing: .05em;
   color: #00adec;
   margin-top: 4px;
   display: none;
  }

  .error-msg.visible {
   display: block;
  }

  .field-error {
   border-color: #00adec !important;
  }

  .rating input:checked+label span {
   background: #00adec;
   color: white;
   border-color: #00adec;
  }
 </style>
</head>

<body class="min-h-screen antialiased bg-[#ebf5f8] text-[#0b1214]"
 style="background:
        radial-gradient(900px 500px at 100% 0%, #ffffff 0%, transparent 55%),
        linear-gradient(180deg, #ebf5f8 0%, #dceef4 100%);">

 <?php include __DIR__ . '/includes/header.php'; ?>

 <main class="max-w-3xl mx-auto px-4 md:px-8 py-12 md:py-16">

  <div class="mb-10">
   <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-3">Section 01</p>
   <h1 class="font-inter text-3xl md:text-4xl font-light text-[#002735]">Event Evaluation</h1>
   <p class="font-serif text-sm text-[#7c868a] mt-3 max-w-xl">
    Your honest feedback helps us make the 9th RSI Forum even better.
   </p>
  </div>

  <?php if ($alreadyDone): ?>
   <div class="bg-white border border-[#7c868a]/25 rounded-lg p-6 md:p-8 mb-6">
    <div class="flex items-start gap-4">
     <div class="w-11 h-11 rounded-full flex items-center justify-center text-white shrink-0"
      style="background: linear-gradient(135deg, #005b7b, #00adec);">
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
       <path d="M5 12l5 5L20 7" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
     </div>
     <div>
      <h2 class="font-inter text-lg font-medium text-[#002735]">Thank you!</h2>
      <p class="font-serif text-sm text-[#7c868a] mt-1">
       You submitted your evaluation on
       <?= htmlspecialchars(date('F j, Y \a\t g:i A', strtotime($existing['submitted_at']))) ?>.
      </p>
     </div>
    </div>
   </div>
  <?php endif; ?>

  <form id="evalForm" novalidate class="bg-white border border-[#7c868a]/25 rounded-lg p-6 md:p-8 <?= $alreadyDone ? 'opacity-70' : '' ?>">
   <input type="hidden" name="action" value="submit_evaluation" />

   <?php
   $questions = [
    'q1Overall'   => ['Overall experience',                    'q1_overall'],
    'q2Content'   => ['Relevance of topics and content',       'q2_content'],
    'q3Speakers'  => ['Quality of speakers and presenters',    'q3_speakers'],
    'q4Venue'     => ['Venue and logistics',                   'q4_venue'],
    'q5Recommend' => ['Would you recommend this forum?',       'q5_recommend'],
   ];
   $i = 0;
   foreach ($questions as $field => [$label, $col]):
    $i++;
    $savedVal = $existing[$col] ?? null;
   ?>
    <fieldset class="mb-8 last:mb-0">
     <legend class="font-inter text-sm font-medium text-[#002735] mb-4">
      <?= $i ?>. <?= htmlspecialchars($label) ?>
     </legend>
     <div class="rating flex flex-wrap gap-2" role="radiogroup" aria-label="<?= htmlspecialchars($label) ?>">
      <?php for ($s = 1; $s <= 5; $s++): ?>
       <input type="radio" id="<?= $field ?>_<?= $s ?>" name="<?= $field ?>" value="<?= $s ?>"
        class="sr-only" <?= $savedVal == $s ? 'checked' : '' ?> <?= $alreadyDone ? 'disabled' : 'required' ?> />
       <label for="<?= $field ?>_<?= $s ?>" class="cursor-pointer">
        <span class="font-inter block text-xs tracking-[0.15em] uppercase py-2.5 px-4 border border-[#7c868a]/30 text-[#35393a] hover:border-[#00adec] transition-colors">
         <?= $s ?> — <?= ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][$s - 1] ?>
        </span>
       </label>
      <?php endfor; ?>
     </div>
     <p class="error-msg" data-error-for="<?= $field ?>"></p>
    </fieldset>
   <?php endforeach; ?>

   <div class="mb-6">
    <label for="comments" class="font-inter block text-sm font-medium text-[#002735] mb-2">
     6. Additional comments <span class="text-[#7c868a] font-normal">(optional)</span>
    </label>
    <textarea id="comments" name="comments" rows="4" <?= $alreadyDone ? 'disabled' : '' ?>
     class="w-full bg-[#ebf5f8]/40 border border-[#7c868a]/30 rounded px-3 py-2 font-serif text-sm text-[#0b1214] focus:outline-none focus:border-[#00adec] transition-colors resize-none"
     placeholder="Anything else you'd like to share?"><?= htmlspecialchars($existing['comments'] ?? '') ?></textarea>
    <p class="error-msg" data-error-for="comments"></p>
   </div>

   <?php if (!$alreadyDone): ?>
    <button type="submit"
     class="w-full bg-[#002735] text-[#ebf5f8] font-inter font-medium text-[11px] tracking-[0.3em] uppercase py-4 px-6 border border-[#002735] hover:bg-[#00adec] hover:text-[#002735] hover:border-[#00adec] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00adec] focus:ring-offset-2">
     Submit Evaluation
    </button>
   <?php endif; ?>
  </form>

  <div class="mt-8 text-center">
   <a href="dashboard.php"
    class="font-inter text-[10px] tracking-[0.3em] uppercase text-[#7c868a] hover:text-[#005b7b] transition-colors">
    ← Back to Dashboard
   </a>
  </div>
 </main>

 <div id="toast"
  class="fixed bottom-6 right-6 z-50 hidden items-center gap-3 bg-[#002735] text-[#ebf5f8] px-4 py-3 rounded shadow-lg font-inter text-xs tracking-wide">
  <span id="toastMsg">Saved.</span>
 </div>

 <script>
  (function() {
   'use strict';
   const form = document.getElementById('evalForm');
   const toast = document.getElementById('toast');
   const tmsg = document.getElementById('toastMsg');

   function clearErrors() {
    form.querySelectorAll('.error-msg').forEach(p => {
     p.classList.remove('visible');
     p.textContent = '';
    });
    form.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
   }

   function paintErrors(errors) {
    Object.entries(errors).forEach(([k, msg]) => {
     const p = form.querySelector(`.error-msg[data-error-for="${k}"]`);
     if (p) {
      p.textContent = msg;
      p.classList.add('visible');
     }
    });
   }

   function showToast(t) {
    tmsg.textContent = t;
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    setTimeout(() => {
     toast.classList.add('hidden');
     toast.classList.remove('flex');
    }, 2200);
   }

   form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (form.classList.contains('opacity-70')) return;
    clearErrors();

    const fd = new FormData(form);
    let res, data;
    try {
     res = await fetch('api/evaluation.php', {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
     });
     data = await res.json();
    } catch (err) {
     console.error(err);
     showToast('Network error. Try again.');
     return;
    }
    if (!data.ok) {
     if (data.errors) paintErrors(data.errors);
     return;
    }
    showToast(data.message || 'Evaluation submitted. Thank you!');
    setTimeout(() => {
     window.location.href = 'dashboard.php';
    }, 900);
   });
  })();
 </script>
</body>

</html>