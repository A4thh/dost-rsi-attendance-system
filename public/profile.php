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
$stmt = db()->prepare(
 'SELECT username, first_name, middle_name, last_name, email
     FROM attendees WHERE id = ? LIMIT 1'
);
$stmt->execute([$_SESSION['attendee_id']]);
$me = $stmt->fetch() ?: [];
?>
<!doctype html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Profile — 8th RSI Forum</title>
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
    <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-3">Account</p>
    <h1 class="font-inter text-3xl md:text-4xl font-light text-[#002735]">Your Profile</h1>
    <p class="font-serif text-sm text-[#7c868a] mt-3">
     Update your details or change your password.
    </p>
   </div>

   <div id="generalBanner" class="error-msg mb-6"></div>

   <!-- ══════════ PERSONAL INFO ══════════ -->
   <section class="bg-white border border-[#7c868a]/25 rounded-lg p-6 md:p-8 mb-6">
    <div class="flex items-baseline justify-between border-b border-[#7c868a]/25 pb-4 mb-6">
     <h2 class="font-inter text-lg font-medium text-[#002735]">Personal Information</h2>
     <span class="font-inter text-[10px] tracking-widest uppercase text-[#7c868a] hidden sm:block">Editable</span>
    </div>

    <form id="infoForm" novalidate class="space-y-6">
     <input type="hidden" name="action" value="update_profile" />

     <div>
      <label for="pfUsername" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Username</label>
      <input id="pfUsername" name="username" type="text" required
       value="<?= htmlspecialchars($me['username'] ?? '') ?>"
       class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base text-[#0b1214] focus:outline-none focus:border-[#00adec] transition-colors" />
      <p class="error-msg" data-error-for="username"></p>
     </div>

     <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <div>
       <label for="pfFirst" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">First Name</label>
       <input id="pfFirst" name="firstName" type="text" required
        value="<?= htmlspecialchars($me['first_name'] ?? '') ?>"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="firstName"></p>
      </div>
      <div>
       <label for="pfMiddle" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Middle Name</label>
       <input id="pfMiddle" name="middleName" type="text"
        value="<?= htmlspecialchars($me['middle_name'] ?? '') ?>"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="middleName"></p>
      </div>
      <div>
       <label for="pfLast" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Last Name</label>
       <input id="pfLast" name="lastName" type="text" required
        value="<?= htmlspecialchars($me['last_name'] ?? '') ?>"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="lastName"></p>
      </div>
     </div>

     <div class="pt-2">
      <button type="submit"
       class="inline-flex items-center gap-2 bg-[#002735] text-[#ebf5f8] font-inter font-medium text-[11px] tracking-[0.3em] uppercase py-3.5 px-6 border border-[#002735] hover:bg-[#00adec] hover:text-[#002735] hover:border-[#00adec] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00adec] focus:ring-offset-2">
       Save Changes
      </button>
     </div>
    </form>
   </section>

   <!-- ══════════ PASSWORD ══════════ -->
   <section class="bg-white border border-[#7c868a]/25 rounded-lg p-6 md:p-8">
    <div class="flex items-baseline justify-between border-b border-[#7c868a]/25 pb-4 mb-6">
     <h2 class="font-inter text-lg font-medium text-[#002735]">Change Password</h2>
     <span class="font-inter text-[10px] tracking-widest uppercase text-[#7c868a] hidden sm:block">Security</span>
    </div>

    <form id="passwordForm" novalidate class="space-y-6">
     <input type="hidden" name="action" value="change_password" />

     <div>
      <label for="cpCurrent" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Current Password</label>
      <input id="cpCurrent" name="currentPassword" type="password" required autocomplete="current-password"
       class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
      <p class="error-msg" data-error-for="currentPassword"></p>
     </div>

     <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <div>
       <label for="cpNew" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">New Password</label>
       <input id="cpNew" name="newPassword" type="password" required autocomplete="new-password"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="newPassword"></p>
      </div>
      <div>
       <label for="cpConfirm" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Confirm New Password</label>
       <input id="cpConfirm" name="confirmPassword" type="password" required autocomplete="new-password"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="confirmPassword"></p>
      </div>
     </div>

     <div class="pt-2">
      <button type="submit"
       class="inline-flex items-center gap-2 bg-[#002735] text-[#ebf5f8] font-inter font-medium text-[11px] tracking-[0.3em] uppercase py-3.5 px-6 border border-[#002735] hover:bg-[#00adec] hover:text-[#002735] hover:border-[#00adec] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00adec] focus:ring-offset-2">
       Update Password
      </button>
     </div>
    </form>
   </section>

   <!-- ══════════ LOGOUT ══════════ -->
   <div class="mt-8 text-center">
    <button type="button" id="logoutBtn"
     class="font-inter text-[10px] tracking-[0.3em] uppercase text-[#7c868a] hover:text-[#005b7b] transition-colors">
     Sign out of this account →
    </button>
   </div>

  </div>
 </main>

 <div id="toast"
  class="fixed bottom-6 right-6 z-50 hidden items-center gap-3 bg-[#002735] text-[#ebf5f8] px-4 py-3 rounded shadow-lg font-inter text-xs tracking-wide">
  <span id="toastMsg">Saved.</span>
 </div>

 <script>
  (function() {
   'use strict';
   const $ = (s, r = document) => r.querySelector(s);
   const $$ = (s, r = document) => [...r.querySelectorAll(s)];

   function clearErrors(scope = document) {
    $$('.error-msg', scope).forEach(p => {
     p.classList.remove('visible');
     p.textContent = '';
    });
    $$('.field-error', scope).forEach(el => el.classList.remove('field-error'));
   }

   function paintErrors(errors, scope = document) {
    Object.entries(errors).forEach(([k, msg]) => {
     const p = scope.querySelector(`.error-msg[data-error-for="${k}"]`);
     if (p) {
      p.textContent = msg;
      p.classList.add('visible');
     }
     const input = scope.querySelector(`[name="${k}"]`);
     if (input) input.classList.add('field-error');
    });
   }

   const banner = $('#generalBanner');
   const toast = $('#toast');
   const tmsg = $('#toastMsg');

   function showBanner(text, isError) {
    banner.textContent = text;
    banner.style.color = isError ? '#00adec' : '#005b7b';
    banner.classList.add('visible');
    setTimeout(() => banner.classList.remove('visible'), 3000);
   }

   function showToast(text) {
    tmsg.textContent = text;
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    setTimeout(() => {
     toast.classList.add('hidden');
     toast.classList.remove('flex');
    }, 2200);
   }

   async function submitForm(form) {
    clearErrors(form);
    banner.classList.remove('visible');

    const fd = new FormData(form);
    let res, data;
    try {
     res = await fetch('api/auth.php', {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
     });
     data = await res.json();
    } catch (err) {
     console.error(err);
     showBanner('Network error. Please try again.', true);
     return null;
    }
    if (!data.ok) {
     if (data.errors?._general) showBanner(data.errors._general, true);
     if (data.errors) paintErrors(data.errors, form);
     return null;
    }
    return data;
   }

   // Info form
   $('#infoForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = await submitForm(e.target);
    if (data?.message) {
     showToast(data.message);
     showBanner(data.message, false);
    }
   });

   // Password form
   $('#passwordForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const np = $('#cpNew').value;
    const cp = $('#cpConfirm').value;
    if (np.length < 8) {
     paintErrors({
      newPassword: 'Password needs at least 8 characters.'
     }, e.target);
     return;
    }
    if (!/[A-Za-z]/.test(np) || !/\d/.test(np)) {
     paintErrors({
      newPassword: 'Password must contain letters and numbers.'
     }, e.target);
     return;
    }
    if (np !== cp) {
     paintErrors({
      confirmPassword: 'Passwords do not match.'
     }, e.target);
     return;
    }
    const data = await submitForm(e.target);
    if (data?.message) {
     e.target.reset();
     showToast(data.message);
     showBanner(data.message, false);
    }
   });

   // Logout
   $('#logoutBtn').addEventListener('click', async () => {
    try {
     const fd = new FormData();
     fd.append('action', 'logout');
     await fetch('api/auth.php', {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
     });
    } catch (err) {
     /* ignore */
    }
    window.location.href = 'auth.php';
   });
  })();
 </script>
</body>

</html>