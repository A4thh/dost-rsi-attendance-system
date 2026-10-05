<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$base = '/dost-rsi-attendance-system/public/';
?>
<footer class="w-full border-t border-[#7c868a]/20 bg-[#0b1214]">
 <div class="max-w-7xl mx-auto px-4 md:px-8 py-10">

  <!-- Top: brand + partners link -->
  <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-8 border-b border-[#7c868a]/20">

   <!-- Brand -->
   <div class="flex items-center gap-3">
    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-inter text-xs font-medium"
     style="background: linear-gradient(135deg, #005b7b, #00adec);">
     R
    </div>
    <div>
     <p class="font-inter text-[11px] tracking-[0.25em] uppercase text-[#ebf5f8]">
      RDC CALABARZON
     </p>
     <p class="font-inter text-[10px] tracking-[0.25em] uppercase text-[#7c868a]">
      8th RSI Forum · 2026
     </p>
    </div>
   </div>

   <!-- Partners button -->
   <a href="<?= $base ?>partners.php"
    class="group inline-flex items-center gap-2 font-inter text-[10px] tracking-[0.3em] uppercase text-[#ebf5f8] border border-[#00adec]/50 hover:bg-[#00adec] hover:text-[#002735] hover:border-[#00adec] py-2.5 px-5 rounded-full transition-all duration-300">
    <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:scale-110"
     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
     <path d="M12 21s-7-5.2-7-11a7 7 0 1 1 14 0c0 5.8-7 11-7 11Z" stroke-linecap="round" stroke-linejoin="round" />
     <circle cx="12" cy="10" r="2.5" stroke="currentColor" />
    </svg>
    Our Partners
    <svg class="w-3 h-3 transition-transform duration-300 group-hover:translate-x-0.5"
     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
     <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
   </a>

  </div>

  <!-- Bottom: copyright strip -->
  <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 font-inter text-[10px] tracking-[0.25em] text-[#7c868a] uppercase">
   <span>© 2026 CALABARZON RDC · RRDIC</span>
   <span>All Rights Reserved</span>
  </div>

 </div>
</footer>