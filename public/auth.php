<?php
session_set_cookie_params([
 'lifetime' => 0,
 'path'     => '/',
 'httponly' => true,
 'samesite' => 'Lax',
 'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

if (!empty($_SESSION['attendee_id'])) {
 header('Location: dashboard.php');
 exit;
}
?>
<!doctype html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Sign In — 8th RSI Forum</title>
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

  html,
  body {
   height: 100%;
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

  .fade-swap {
   animation: fadeSwap .35s ease;
  }

  @keyframes fadeSwap {
   from {
    opacity: 0;
    transform: translateY(6px);
   }

   to {
    opacity: 1;
    transform: none;
   }
  }
 </style>
</head>

<body class="min-h-screen antialiased">

 <main class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

  <!-- ══════════ BRAND SIDE ══════════ -->
  <aside class="relative hidden lg:flex flex-col justify-between p-12 overflow-hidden text-[#ebf5f8]"
   style="background:
            radial-gradient(1200px 800px at -10% -10%, #005b7b 0%, transparent 60%),
            radial-gradient(900px 700px at 110% 110%, #00adec22 0%, transparent 55%),
            linear-gradient(160deg, #002735 0%, #002735 55%, #003a4d 100%);">

   <svg class="absolute inset-0 h-full w-full pointer-events-none opacity-[0.10]" aria-hidden="true">
    <defs>
     <pattern id="auth-grid" width="60" height="60" patternUnits="userSpaceOnUse" patternTransform="rotate(10)">
      <path d="M0 0L60 60M60 0L0 60" fill="none" stroke="#00adec" stroke-width="0.5" />
     </pattern>
    </defs>
    <rect width="100%" height="100%" fill="url(#auth-grid)" />
   </svg>

   <div class="relative z-10">
    <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-3">RSI Forum · 2026</p>
    <h1 class="font-inter text-3xl xl:text-4xl font-light leading-tight max-w-md">
     8th Research,<br />Statistics &amp; Innovation Forum
    </h1>
   </div>

   <div class="relative z-10 max-w-md">
    <div class="h-px w-12 bg-[#00adec] mb-6"></div>
    <p class="font-serif text-sm leading-relaxed text-[#ebf5f8]/80">
     Fostering a Resilient Tomorrow: Mobilizing Data and Digital Innovation
     for Sustainable Development Goal Progress, Food, Energy, and Tourism.
    </p>
    <dl class="mt-10 space-y-4 font-inter text-[10px] uppercase tracking-widest text-[#ebf5f8]/60">
     <div class="flex gap-4">
      <dt class="text-[#00adec] w-16">Date</dt>
      <dd class="font-serif normal-case tracking-normal text-[#ebf5f8]">October 21, 2026</dd>
     </div>
     <div class="flex gap-4">
      <dt class="text-[#00adec] w-16">Venue</dt>
      <dd class="font-serif normal-case tracking-normal text-[#ebf5f8]">Batangas State University</dd>
     </div>
    </dl>
   </div>

   <p class="relative z-10 font-inter text-[10px] tracking-[0.3em] uppercase text-[#ebf5f8]/40">
    CALABARZON · RDC · RRDIC
   </p>
  </aside>

  <!-- ══════════ FORM SIDE ══════════ -->
  <section class="relative flex items-center justify-center px-6 py-12 lg:py-16 bg-[#ebf5f8]"
   style="background:
              radial-gradient(900px 500px at 100% 0%, #ffffff 0%, transparent 55%),
              linear-gradient(180deg, #ebf5f8 0%, #dceef4 100%);">

   <div class="w-full max-w-md">

    <div class="lg:hidden mb-8 text-center">
     <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-2">RSI Forum · 2026</p>
     <h1 class="font-inter text-xl font-light text-[#002735]">8th Research, Statistics &amp; Innovation Forum</h1>
    </div>

    <!-- Segmented tab -->
    <div class="relative flex p-1 bg-white/70 backdrop-blur border border-[#7c868a]/25 rounded-full mb-8 shadow-sm">
     <span id="tabSlider"
      class="absolute top-1 bottom-1 left-1 w-[calc(50%-4px)] rounded-full transition-transform duration-300 ease-out"
      style="background: linear-gradient(135deg, #005b7b, #00adec);"></span>
     <button type="button" data-tab="login"
      class="tab-btn relative z-10 flex-1 py-2.5 font-inter text-[11px] tracking-[0.25em] uppercase text-white">
      Log In
     </button>
     <button type="button" data-tab="signup"
      class="tab-btn relative z-10 flex-1 py-2.5 font-inter text-[11px] tracking-[0.25em] uppercase text-[#35393a]">
      Sign Up
     </button>
    </div>

    <div id="generalError" class="error-msg mb-4"></div>

    <!-- ══════════ LOGIN VIEW ══════════ -->
    <div id="view-login" class="fade-swap">
     <h2 class="font-inter text-2xl font-light text-[#002735]">Welcome back</h2>
     <p class="font-serif text-sm text-[#7c868a] mt-1 mb-8">Sign in to access your dashboard.</p>

     <form id="loginForm" novalidate autocomplete="on" class="space-y-6">
      <input type="hidden" name="action" value="login" />
      <div>
       <label for="loginUsername" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Username</label>
       <input id="loginUsername" name="username" type="text" required autocomplete="username"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base text-[#0b1214] focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="username"></p>
      </div>
      <div>
       <label for="loginPassword" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Password</label>
       <input id="loginPassword" name="password" type="password" required autocomplete="current-password"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base text-[#0b1214] focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="password"></p>
      </div>

      <div class="flex justify-end">
       <button type="button" id="toForgot"
        class="font-inter text-[10px] tracking-[0.25em] uppercase text-[#005b7b] hover:text-[#00adec] transition-colors">
        Forgot Password?
       </button>
      </div>

      <button type="submit"
       class="w-full bg-[#002735] text-[#ebf5f8] font-inter font-medium text-[11px] tracking-[0.3em] uppercase py-4 px-6 border border-[#002735] hover:bg-[#00adec] hover:text-[#002735] hover:border-[#00adec] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00adec] focus:ring-offset-2">
       Log In
      </button>
     </form>
    </div>

    <!-- ══════════ SIGNUP VIEW ══════════ -->
    <div id="view-signup" class="hidden fade-swap">
     <h2 class="font-inter text-2xl font-light text-[#002735]">Create an account</h2>
     <p class="font-serif text-sm text-[#7c868a] mt-1 mb-8">Register once, then complete your event registration inside.</p>

     <form id="signupForm" novalidate autocomplete="on" class="space-y-5">
      <input type="hidden" name="action" value="signup" />

      <div>
       <label for="suUsername" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Username</label>
       <input id="suUsername" name="username" type="text" required autocomplete="username"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base text-[#0b1214] focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="username"></p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
       <div>
        <label for="suFirst" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">First Name <span class="text-[#00adec]">*</span></label>
        <input id="suFirst" name="firstName" type="text" required autocomplete="given-name"
         class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
        <p class="error-msg" data-error-for="firstName"></p>
       </div>
       <div>
        <label for="suMiddle" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Middle Name</label>
        <input id="suMiddle" name="middleName" type="text" autocomplete="additional-name"
         class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
        <p class="error-msg" data-error-for="middleName"></p>
       </div>
       <div>
        <label for="suLast" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Last Name <span class="text-[#00adec]">*</span></label>
        <input id="suLast" name="lastName" type="text" required autocomplete="family-name"
         class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
        <p class="error-msg" data-error-for="lastName"></p>
       </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
       <div>
        <label for="suPassword" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Password</label>
        <input id="suPassword" name="password" type="password" required autocomplete="new-password"
         class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
        <p class="error-msg" data-error-for="password"></p>
       </div>
       <div>
        <label for="suConfirm" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Confirm Password</label>
        <input id="suConfirm" name="confirmPassword" type="password" required autocomplete="new-password"
         class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
        <p class="error-msg" data-error-for="confirmPassword"></p>
       </div>
      </div>

      <label class="flex items-start gap-3 cursor-pointer group pt-1">
       <span class="relative flex items-center justify-center mt-0.5">
        <input type="checkbox" name="agreeTerms" class="sr-only peer" required />
        <span class="w-5 h-5 border border-[#7c868a] peer-checked:bg-[#00adec] peer-checked:border-[#00adec] peer-focus:ring-2 peer-focus:ring-[#00adec] peer-focus:ring-offset-2 transition-colors flex items-center justify-center">
         <svg class="w-3 h-3 text-white hidden peer-checked:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
          <path d="M5 12l5 5L20 7" stroke-linecap="round" stroke-linejoin="round" />
         </svg>
        </span>
       </span>
       <span class="font-serif text-xs text-[#35393a] leading-relaxed">
        I agree to the <a href="#" class="text-[#005b7b] underline underline-offset-2 hover:text-[#00adec]">Terms of Service</a>
        and <a href="#" class="text-[#005b7b] underline underline-offset-2 hover:text-[#00adec]">Privacy Policy</a>,
        including processing of my data under RA 10173.
        <p class="error-msg" data-error-for="agreeTerms"></p>
       </span>
      </label>

      <button type="submit"
       class="w-full bg-[#002735] text-[#ebf5f8] font-inter font-medium text-[11px] tracking-[0.3em] uppercase py-4 px-6 border border-[#002735] hover:bg-[#00adec] hover:text-[#002735] hover:border-[#00adec] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00adec] focus:ring-offset-2 mt-2">
       Sign Up
      </button>
     </form>
    </div>

    <!-- ══════════ FORGOT PASSWORD VIEW ══════════ -->
    <div id="view-forgot" class="hidden fade-swap">
     <button type="button" id="backToLogin"
      class="font-inter text-[10px] tracking-[0.25em] uppercase text-[#7c868a] hover:text-[#00adec] mb-6 inline-flex items-center gap-2 transition-colors">
      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
       <path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      Back to login
     </button>

     <h2 class="font-inter text-2xl font-light text-[#002735]">Reset your password</h2>
     <p id="forgotSubtext" class="font-serif text-sm text-[#7c868a] mt-1 mb-8">
      Enter your username to continue.
     </p>

     <form id="lookupForm" novalidate class="space-y-6">
      <input type="hidden" name="action" value="lookup_username" />
      <div>
       <label for="fpUsername" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Username</label>
       <input id="fpUsername" name="username" type="text" required autocomplete="username"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="username"></p>
      </div>
      <button type="submit"
       class="w-full bg-[#002735] text-[#ebf5f8] font-inter font-medium text-[11px] tracking-[0.3em] uppercase py-4 border border-[#002735] hover:bg-[#00adec] hover:text-[#002735] hover:border-[#00adec] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00adec] focus:ring-offset-2">
       Continue
      </button>
     </form>

     <form id="resetForm" novalidate class="hidden space-y-6">
      <input type="hidden" name="action" value="reset_password" />
      <div>
       <label for="fpPassword" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">New Password</label>
       <input id="fpPassword" name="password" type="password" required autocomplete="new-password"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="password"></p>
      </div>
      <div>
       <label for="fpConfirm" class="font-inter block text-[10px] tracking-[0.25em] uppercase text-[#35393a] mb-2">Confirm New Password</label>
       <input id="fpConfirm" name="confirmPassword" type="password" required autocomplete="new-password"
        class="w-full bg-transparent border-0 border-b border-[#7c868a]/40 px-0 py-2 font-serif text-base focus:outline-none focus:border-[#00adec] transition-colors" />
       <p class="error-msg" data-error-for="confirmPassword"></p>
      </div>
      <button type="submit"
       class="w-full bg-[#00adec] text-[#002735] font-inter font-medium text-[11px] tracking-[0.3em] uppercase py-4 border border-[#00adec] hover:bg-[#005b7b] hover:text-[#ebf5f8] hover:border-[#005b7b] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00adec] focus:ring-offset-2">
       Change Password
      </button>
     </form>

     <div id="resetSuccess" class="hidden text-center py-8">
      <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-4"
       style="background: linear-gradient(135deg, #005b7b, #00adec);">
       <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M5 12l5 5L20 7" stroke-linecap="round" stroke-linejoin="round" />
       </svg>
      </div>
      <h3 class="font-inter text-lg text-[#002735] mb-2">Password updated</h3>
      <p class="font-serif text-sm text-[#7c868a] mb-6">You can now log in with your new password.</p>
      <button type="button" id="successToLogin"
       class="font-inter text-[10px] tracking-[0.3em] uppercase text-[#005b7b] hover:text-[#00adec] transition-colors">
       Go to Login →
      </button>
     </div>
    </div>

    <p class="font-inter text-[10px] tracking-[0.25em] uppercase text-[#7c868a] text-center mt-10">
     © 2026 CALABARZON RDC · RRDIC
    </p>
   </div>
  </section>
 </main>

 <script>
  (function() {
   'use strict';

   const $ = (s, r = document) => r.querySelector(s);
   const $$ = (s, r = document) => [...r.querySelectorAll(s)];

   const tabSlider = $('#tabSlider');
   const tabBtns = $$('.tab-btn');
   const views = {
    login: $('#view-login'),
    signup: $('#view-signup'),
    forgot: $('#view-forgot'),
   };

   function showView(name, opts = {}) {
    Object.entries(views).forEach(([k, el]) => {
     el.classList.toggle('hidden', k !== name);
     if (k === name) el.classList.add('fade-swap');
    });

    if (name === 'login' || name === 'signup') {
     const idx = name === 'login' ? 0 : 1;
     tabSlider.style.transform = `translateX(${idx * 100}%)`;
     tabBtns.forEach((b, i) => {
      b.classList.toggle('text-white', i === idx);
      b.classList.toggle('text-[#35393a]', i !== idx);
     });
    }

    if (name === 'login') {
     $('#loginForm').reset();
    } else if (name === 'signup') {
     $('#signupForm').reset();
    } else if (name === 'forgot') {
     $('#lookupForm').reset();
     $('#resetForm').reset();
     $('#lookupForm').classList.remove('hidden');
     $('#resetForm').classList.add('hidden');
     $('#resetSuccess').classList.add('hidden');
     $('#forgotSubtext').textContent = 'Enter your username to continue.';
    }

    if (!opts.keepBanner) {
     clearAllErrors();
     const b = $('#generalError');
     b.classList.remove('visible');
     b.textContent = '';
    }
   }

   tabBtns.forEach(btn => btn.addEventListener('click', () => showView(btn.dataset.tab)));
   $('#toForgot').addEventListener('click', () => showView('forgot'));

   $('#backToLogin').addEventListener('click', () => {
    $('#resetForm').classList.add('hidden');
    $('#lookupForm').classList.remove('hidden');
    $('#resetSuccess').classList.add('hidden');
    $('#forgotSubtext').textContent = 'Enter your username to continue.';
    showView('login');
   });

   $('#successToLogin').addEventListener('click', () => {
    $('#resetForm').classList.add('hidden');
    $('#lookupForm').classList.remove('hidden');
    $('#resetSuccess').classList.add('hidden');
    showView('login');
   });

   function clearAllErrors(scope = document) {
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

   $('#suPassword').addEventListener('input', (e) => {
    const val = e.target.value;
    const p = $('#signupForm .error-msg[data-error-for="password"]');
    if (val.length > 0 && val.length < 8) {
     p.textContent = `Password needs at least 8 characters (${val.length}/8).`;
     p.classList.add('visible');
     e.target.classList.add('field-error');
    } else {
     p.classList.remove('visible');
     e.target.classList.remove('field-error');
    }
   });

   async function submitForm(form) {
    clearAllErrors(form);

    const banner = $('#generalError');
    banner.classList.remove('visible');
    banner.style.color = '#00adec';
    banner.textContent = '';

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
     console.error('[auth] fetch failed', err);
     banner.textContent = 'Network error. Please try again.';
     banner.classList.add('visible');
     return null;
    }

    if (!data.ok) {
     if (data.errors?._general) {
      banner.textContent = data.errors._general;
      banner.classList.add('visible');
      delete data.errors._general;
     }
     if (data.errors) paintErrors(data.errors, form);
     return null;
    }
    return data;
   }

   $('#loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = await submitForm(e.target);
    if (data?.redirect) window.location.href = data.redirect;
   });

   $('#signupForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const pass = $('#suPassword').value;
    const conf = $('#suConfirm').value;
    const pre = {};

    if (pass.length < 8)
     pre.password = 'Password needs at least 8 characters.';
    else if (!/[A-Za-z]/.test(pass) || !/\d/.test(pass))
     pre.password = 'Password must contain letters and numbers.';

    if (pass !== conf)
     pre.confirmPassword = 'Passwords do not match.';

    if (!$('#signupForm [name="agreeTerms"]').checked)
     pre.agreeTerms = 'You must agree to the Terms and Privacy Policy.';

    if (Object.keys(pre).length) {
     paintErrors(pre, e.target);
     return;
    }

    const data = await submitForm(e.target);
    if (!data) return;

    e.target.reset();
    showView('login', {
     keepBanner: true
    });

    const banner = $('#generalError');
    banner.textContent = 'Account created successfully. Please log in.';
    banner.style.color = '#005b7b';
    banner.classList.add('visible');

    $('#loginUsername').focus();
   });

   $('#lookupForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = await submitForm(e.target);
    if (data?.next === 'reset') {
     $('#lookupForm').classList.add('hidden');
     $('#resetForm').classList.remove('hidden');
     $('#forgotSubtext').textContent = 'Choose a new password for your account.';
    }
   });

   $('#resetForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const pass = $('#fpPassword').value;
    const conf = $('#fpConfirm').value;
    const pre = {};

    if (pass.length < 8)
     pre.password = 'Password needs at least 8 characters.';
    else if (!/[A-Za-z]/.test(pass) || !/\d/.test(pass))
     pre.password = 'Password must contain letters and numbers.';

    if (pass !== conf)
     pre.confirmPassword = 'Passwords do not match.';

    if (Object.keys(pre).length) {
     paintErrors(pre, e.target);
     return;
    }

    const data = await submitForm(e.target);
    if (data?.message) {
     $('#resetForm').classList.add('hidden');
     $('#resetSuccess').classList.remove('hidden');
     $('#forgotSubtext').textContent = 'Password successfully updated.';
     e.target.reset();
    }
   });

   showView('login');
  })();
 </script>
</body>

</html>