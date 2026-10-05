<?php
session_set_cookie_params([
 'lifetime' => 0,
 'path'     => '/',
 'httponly' => true,
 'samesite' => 'Lax',
 'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

require __DIR__ . '/api/config.php';

$institutions = [
 ['name' => 'Cordillera Regional Development Council',                       'logo' => 'cordillera.jpg', 'region' => 'CAR'],
 ['name' => 'Regional Development Council Region I',                         'logo' => 'region1.jpg',    'region' => 'Region I'],
 ['name' => 'Regional Development Council Region II',                        'logo' => 'region2.png',    'region' => 'Region II'],
 ['name' => 'Regional Development Council Region V (Bicol)',                 'logo' => 'region5.jpg',    'region' => 'Region V'],
 ['name' => 'Regional Development Council Region VI',                        'logo' => 'region6.jpg',    'region' => 'Region VI'],
 ['name' => 'Regional Development Council Region VII',                       'logo' => 'region7.jpg',    'region' => 'Region VII'],
 ['name' => 'Regional Development Council Region VIII',                      'logo' => 'region8.jpg',    'region' => 'Region VIII'],
 ['name' => 'Regional Development Council Region IX',                        'logo' => 'region9.jpg',    'region' => 'Region IX'],
 ['name' => 'Regional Development Council Region X',                         'logo' => 'region10.png',   'region' => 'Region X'],
 ['name' => 'Regional Development Council Region XI',                        'logo' => 'region11.jpg',   'region' => 'Region XI'],
 ['name' => 'Regional Development Council Region XII (SOCCSKSARGEN)',        'logo' => 'region12.jpg',   'region' => 'Region XII'],
 ['name' => 'Regional Development Council Caraga',                           'logo' => 'region13.png',   'region' => 'Region XIII'],
 ['name' => 'MIMAROPA Regional Development Council',                         'logo' => 'mimaropa.jpg',   'region' => 'MIMAROPA'],
 ['name' => 'Negros Island Region Development Council',                      'logo' => 'nir.png',        'region' => 'NIR'],
];
?>
<!doctype html>
<html class="scroll-smooth" lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Partner Institutions — 8th RSI Forum</title>
 <link rel="stylesheet" href="./css/output.css" />
 <link rel="stylesheet" href="./css/globals.css" />
 <link rel="preconnect" href="https://fonts.googleapis.com" />
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
 <link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap"
  rel="stylesheet" />
 <style>
  .font-inter {
   font-family: "Inter", system-ui, -apple-system, sans-serif;
  }

  .font-serif {
   font-family: "Source Serif 4", Georgia, serif;
  }

  .partners-grid-paper {
   background-image:
    linear-gradient(rgba(0, 91, 123, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 91, 123, 0.05) 1px, transparent 1px);
   background-size: 32px 32px;
  }
 </style>
</head>

<body class="min-h-screen antialiased bg-[#ebf5f8] text-[#0b1214]">

 <?php include __DIR__ . '/includes/header.php'; ?>

 <!-- ══════════ HERO ══════════ -->
 <section class="relative overflow-hidden"
  style="background:
     radial-gradient(1200px 600px at -10% -20%, #005b7b 0%, transparent 60%),
     radial-gradient(900px 500px at 110% 120%, #00adec33 0%, transparent 55%),
     linear-gradient(160deg, #002735 0%, #002735 55%, #003a4d 100%);">

  <!-- Decorative grid -->
  <svg class="absolute inset-0 h-full w-full pointer-events-none opacity-[0.10]" aria-hidden="true">
   <defs>
    <pattern id="partners-grid" width="60" height="60" patternUnits="userSpaceOnUse" patternTransform="rotate(10)">
     <path d="M0 0L60 60M60 0L0 60" fill="none" stroke="#00adec" stroke-width="0.5" />
    </pattern>
   </defs>
   <rect width="100%" height="100%" fill="url(#partners-grid)" />
  </svg>

  <div class="relative z-10 max-w-5xl mx-auto px-4 md:px-8 py-20 md:py-28 text-center">
   <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-4">
    With Appreciation
   </p>
   <h1 class="font-inter text-3xl md:text-5xl lg:text-6xl font-light tracking-tight text-[#ebf5f8] leading-tight max-w-4xl mx-auto">
    Dedicated to our<br class="hidden sm:block" /> partner institutions
   </h1>
   <div class="h-px w-16 bg-[#00adec] mx-auto my-8"></div>
   <p class="font-serif text-sm md:text-base leading-relaxed text-[#ebf5f8]/80 max-w-2xl mx-auto">
    With gratitude, we recognize our partner institutions whose collaboration and commitment to research, statistics, and innovation support regional development. This forum is dedicated to your continued partnership and shared work toward a more resilient future.
   </p>
  </div>

  <!-- Wave → next section -->
  <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-b from-transparent to-[#ebf5f8]"></div>
 </section>

 <!-- ══════════ MAIN ══════════ -->
 <main class="partners-grid-paper">
  <div class="max-w-6xl mx-auto px-4 md:px-8 py-16 md:py-20">

   <!-- Section intro -->
   <div class="flex items-baseline justify-between border-b border-[#7c868a]/25 pb-4 mb-10">
    <div>
     <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-2">
      Regional Councils
     </p>
     <h2 class="font-inter text-2xl md:text-3xl font-light text-[#002735]">
      Partner Institutions
     </h2>
    </div>
    <span class="font-inter text-[10px] tracking-widest uppercase text-[#7c868a] hidden sm:block">
     <?= count($institutions) ?> institutions
    </span>
   </div>

   <!-- Grid -->
   <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
    <?php foreach ($institutions as $institution): ?>
     <li class="group relative flex flex-col bg-white border border-[#7c868a]/25 rounded-lg overflow-hidden hover:border-[#00adec]/60 hover:shadow-[0_10px_30px_-12px_rgba(0,173,236,0.4)] transition-all duration-300">

      <!-- Top accent line -->
      <div class="h-[3px] w-full bg-gradient-to-r from-[#005b7b] to-[#00adec] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

      <!-- Logo area -->
      <div class="flex-1 flex items-center justify-center p-5 sm:p-6 min-h-[140px] sm:min-h-[160px] bg-gradient-to-b from-white to-[#f4f9fb]">
       <img
        src="./assets/images/affiliates/<?= htmlspecialchars($institution['logo'], ENT_QUOTES, 'UTF-8') ?>"
        alt="<?= htmlspecialchars($institution['name'], ENT_QUOTES, 'UTF-8') ?> logo"
        class="max-h-24 sm:max-h-28 w-full object-contain filter grayscale-[40%] opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
        loading="lazy" />
      </div>

      <!-- Name + region -->
      <div class="border-t border-[#7c868a]/20 px-4 py-4 text-center">
       <p class="font-inter text-[10px] tracking-[0.25em] uppercase text-[#00adec] mb-1">
        <?= htmlspecialchars($institution['region'], ENT_QUOTES, 'UTF-8') ?>
       </p>
       <p class="font-serif text-xs sm:text-sm leading-snug text-[#002735]">
        <?= htmlspecialchars($institution['name'], ENT_QUOTES, 'UTF-8') ?>
       </p>
      </div>
     </li>
    <?php endforeach; ?>
   </ul>

   <!-- Closing note -->
   <div class="mt-16 max-w-3xl mx-auto text-center">
    <div class="h-px w-12 bg-[#00adec] mx-auto mb-6"></div>
    <p class="font-serif text-sm leading-relaxed text-[#35393a]">
     This forum would not be possible without the continued trust and partnership of these councils. Together, we advance research, statistics, and innovation across the regions.
    </p>
   </div>

  </div>
 </main>

 <?php include __DIR__ . '/includes/footer.php'; ?>
</body>

</html>