<?php
if (session_status() === PHP_SESSION_NONE) {
 session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
$isLoggedIn  = !empty($_SESSION['attendee_id']);
$navUser     = $isLoggedIn ? (string)($_SESSION['username'] ?? '') : '';
$navInitial  = $navUser !== '' ? strtoupper($navUser[0]) : '?';

// Root-relative nav links so this works from any folder depth
$base = '/dost-rsi-attendance-system/public/';
?>
<header class="font-inter w-full border-b border-[#7c868a]/20 bg-white/90 backdrop-blur-sm">
 <div class="max-w-7xl mx-auto flex items-center justify-between px-4 md:px-8 py-4">

  <!-- Left: brand -->
  <a href="<?= $base ?>index.php" class="flex items-center gap-2 text-[#002735]">
   <img src="<?= $base ?>assets/icons/rdc-calabarzon-logo-nobg.png" class="w-7 h-7" alt="">
   <span class="font-medium tracking-wide text-sm uppercase">RDC · 8th RSI Forum</span>
  </a>

  <!-- Center: main nav -->
  <nav class="hidden md:flex items-center gap-6 text-xs uppercase tracking-widest text-[#35393a]">
   <a href="<?= $base ?>index.php"
    class="<?= $currentPage === 'index.php' ? 'text-[#00adec]' : 'hover:text-[#00adec]' ?>"
    <?= $currentPage === 'index.php' ? 'aria-current="page"' : '' ?>>
    About
   </a>
   <a href="<?= $base ?>register.php"
    class="<?= $currentPage === 'register.php' ? 'text-[#00adec]' : 'hover:text-[#00adec]' ?>"
    <?= $currentPage === 'register.php' ? 'aria-current="page"' : '' ?>>
    Register
   </a>
   <?php if ($isLoggedIn): ?>
    <a href="<?= $base ?>dashboard.php"
     class="<?= $currentPage === 'dashboard.php' ? 'text-[#00adec]' : 'hover:text-[#00adec]' ?>"
     <?= $currentPage === 'dashboard.php' ? 'aria-current="page"' : '' ?>>
     Dashboard
    </a>
   <?php endif; ?>
  </nav>

  <!-- Right: auth actions -->
  <div class="flex items-center gap-2 sm:gap-3">

   <?php if ($isLoggedIn): ?>
    <!-- User chip -->
    <a href="<?= $base ?>profile.php"
     class="hidden sm:flex items-center gap-2 pr-3 border-r border-[#7c868a]/25 group">
     <span class="w-7 h-7 rounded-full flex items-center justify-center text-white font-inter text-[11px] font-medium"
      style="background: linear-gradient(135deg, #005b7b, #00adec);">
      <?= htmlspecialchars($navInitial) ?>
     </span>
     <span class="font-inter text-xs text-[#35393a] group-hover:text-[#00adec] transition-colors">
      <?= htmlspecialchars($navUser) ?>
     </span>
    </a>

    <!-- Logout -->
    <button type="button" id="navLogoutBtn"
     class="group inline-flex items-center gap-1.5 font-inter text-[10px] tracking-[0.25em] uppercase text-[#35393a] hover:text-[#00adec] transition-colors px-2 py-1.5 rounded">
     <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
      <path d="M15 12H4M10 6l-6 6 6 6M14 5h4a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4"
       stroke-linecap="round" stroke-linejoin="round" />
     </svg>
     <span class="hidden sm:inline">Logout</span>
    </button>
   <?php else: ?>
    <a href="<?= $base ?>auth.php"
     class="font-inter text-[10px] tracking-[0.25em] uppercase text-[#35393a] hover:text-[#00adec] transition-colors px-3 py-2 rounded">
     Log In
    </a>
    <a href="<?= $base ?>auth.php"
     class="font-inter text-[10px] tracking-[0.25em] uppercase bg-[#002735] text-[#ebf5f8] hover:bg-[#00adec] hover:text-[#002735] transition-colors px-4 py-2 rounded">
     Sign Up
    </a>
   <?php endif; ?>
  </div>
 </div>
</header>

<?php if ($isLoggedIn): ?>
 <script>
  (function() {
   'use strict';
   var btn = document.getElementById('navLogoutBtn');
   if (!btn) return;
   btn.addEventListener('click', async function() {
    btn.disabled = true;
    btn.style.opacity = '0.5';
    try {
     var fd = new FormData();
     fd.append('action', 'logout');
     await fetch('<?= $base ?>api/auth.php', {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
     });
    } catch (e) {
     /* ignore */
    }
    window.location.href = '<?= $base ?>auth.php';
   });
  })();
 </script>
<?php endif; ?>