<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<header class="font-inter w-full border-b border-[#7c868a]/20 bg-white">
 <div class="max-w-7xl mx-auto flex items-center justify-between px-4 md:px-8 py-4">
  <a href="./index.php" class="flex items-center gap-2 text-[#002735]">
   <img src="../public/assets/icons/rdc-calabarzon-logo-nobg.png" class="w-7 h-7" alt="">
   <span class="font-medium tracking-wide text-sm uppercase">RDC · 8th RSI Forum</span>
  </a>
  <nav class="flex items-center gap-6 text-xs uppercase tracking-widest text-[#35393a]">
   <a href="index.php"
    class="<?= $currentPage === 'index.php' ? 'text-[#00adec]' : 'hover:text-[#00adec]' ?>"
    <?= $currentPage === 'index.php' ? 'aria-current="page"' : '' ?>>
    About
   </a>
   <a href="register.php"
    class="<?= $currentPage === 'register.php' ? 'text-[#00adec]' : 'hover:text-[#00adec]' ?>"
    <?= $currentPage === 'register.php' ? 'aria-current="page"' : '' ?>>
    Register
   </a>
  </nav>
 </div>
</header>