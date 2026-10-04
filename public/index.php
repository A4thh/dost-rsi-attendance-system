<!doctype html>
<html class="scroll-smooth" lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>About — 8th RSI Forum</title>
 <link rel="stylesheet" href="./css/output.css" />
 <link rel="stylesheet" href="./css/globals.css" />
 <link rel="preconnect" href="https://fonts.googleapis.com" />
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
 <link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap"
  rel="stylesheet" />

 <style>
  .font-inter {
   font-family:
    "Inter",
    system-ui,
    -apple-system,
    sans-serif;
  }

  .font-serif {
   font-family: "Source Serif 4", Georgia, "Times New Roman", serif;
  }
 </style>
</head>

<body>
 <?php include '../public/includes/header.php' ?>

 <section
  class="relative min-h-screen overflow-hidden bg-cover bg-center bg-no-repeat"
  style="background-image: url(&quot;../public/assets/images/home-background.png&quot;)">
  <svg
   class="absolute inset-0 h-full w-full pointer-events-none"
   xmlns="http://www.w3.org/2000/svg"
   aria-hidden="true">
   <defs>
    <pattern
     id="hero-grid"
     width="60"
     height="60"
     patternUnits="userSpaceOnUse"
     patternTransform="rotate(10)">
     <path
      d="M0 0L60 60M60 0L0 60"
      fill="none"
      stroke="#00adec"
      stroke-width="0.4"
      opacity="0.15" />
    </pattern>
   </defs>

   <rect width="100%" height="100%" fill="url(#hero-grid)" />
  </svg>

  <div
   class="absolute inset-0"
   style="
     background: linear-gradient(
      to bottom,
      rgba(235, 245, 248, 0.35) 0%,
      rgba(235, 245, 248, 0.2) 40%,
      #46a0c1bb  88%,
      #46a0c1 100%
     );
    "></div>

  <div
   class="relative z-10 flex min-h-screen items-center justify-center px-6 text-center">
   <div class="max-w-4xl">
    <h1
     class="font-inter text-center text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-[#002735] max-w-4xl leading-none"
     style="text-shadow: 0 1px 4px rgba(235, 245, 248, 0.6)">
     8th Research, Statistics, and Innovation Forum
    </h1>

    <div
     class="mt-5 flex md:items-center justify-center gap-2 text-xs sm:text-base md:text-lg font-normal text-[#35393a]">
     <svg
      class="w-4 h-4 md:w-5 md:h-5 text-[#005b7b]"
      fill="none"
      stroke="currentColor"
      stroke-width="1.5"
      viewBox="0 0 24 24"
      aria-hidden="true">
      <rect x="3" y="5" width="18" height="16" rx="1" stroke="currentColor" />
      <path
       d="M3 9h18M8 3v4M16 3v4"
       stroke="currentColor"
       stroke-linecap="round" />
     </svg>

     <span class="font-inter">
      Oct 21, 2026, 08:00 AM – 05:00 PM
     </span>
    </div>

    <div
     class="mt-2 flex md:items-center justify-center gap-2 text-xs sm:text-sm text-[#35393a]">
     <svg
      class="w-3.5 h-3.5 md:w-4 md:h-4 text-[#005b7b]"
      fill="none"
      stroke="currentColor"
      stroke-width="1.5"
      viewBox="0 0 24 24"
      aria-hidden="true">
      <path
       d="M12 21s-7-5.2-7-11a7 7 0 1 1 14 0c0 5.8-7 11-7 11Z"
       stroke="currentColor"
       stroke-linejoin="round" />
      <circle cx="12" cy="10" r="2.5" stroke="currentColor" />
     </svg>

     <span class="font-inter w-fit">
      Batangas State University | Alangilan, Batangas City
     </span>
    </div>
    <!-- <a
      href="#about"
      class="group inline-flex items-center justify-center gap-2 self-center font-inter text-xs font-medium uppercase tracking-widest text-[#002735] mt-5 bg-gray-300/40 py-3 px-4 rounded-xl"
     >
      <span>More Info</span>

      <svg
       class="h-4 w-4 transition-transform duration-300 group-hover:translate-y-0.5"
       fill="none"
       stroke="currentColor"
       stroke-width="1.5"
       viewBox="0 0 24 24"
       aria-hidden="true"
      >
       <path
        d="M12 5v14M7 14l5 5 5-5"
        stroke-linecap="round"
        stroke-linejoin="round"
       />
      </svg>
     </a> -->
    <a
     href="#about"
     class="relative inline-flex items-center justify-center px-8 py-3.5 text-sm font-base text-white transition-all duration-300 rounded-full group overflow-hidden bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 hover:border-white/40 hover:shadow-[0_8px_32px_0_rgba(255,255,255,0.2)] active:scale-95 mt-5">
     <!-- Left-to-Right and Back Shimmer -->
     <span
      class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/80 via-30% to-transparent -translate-x-full animate-[shimmer-pingpong_2s_infinite_ease-in-out_alternate] drop-shadow-[0_0_12px_rgba(255,255,255,0.9)]"></span>

     <!-- Ambient Glow Circle (also ping-pongs across) -->
     <span
      class="absolute -top-10 w-24 h-24 bg-white/70 rounded-full blur-xl animate-[pingpong_2s_infinite_ease-in-out_alternate]"></span>

     <span
      class="font-inter text-[#002735] relative z-10 flex items-center gap-2 tracking-wide drop-shadow-md me-2">
      More Info
     </span>

     <svg
      class="h-4 w-4 transition-transform duration-300 group-hover:translate-y-0.5 text-[#002735] relative z-10"
      fill="none"
      stroke="currentColor"
      stroke-width="1.5"
      viewBox="0 0 24 24"
      aria-hidden="true">
      <path
       d="M12 5v14M7 14l5 5 5-5"
       stroke-linecap="round"
       stroke-linejoin="round" />
     </svg>
    </a>
   </div>
  </div>
 </section>

 <main id="about" class="bg-[#46a0c1]">
  <div class="mx-auto w-full max-w-6xl px-4 py-12 md:px-8 md:py-16">
   <div
    class="grid grid-cols-1 lg:grid-cols-[1.9fr_1fr] gap-5 lg:gap-6 items-start">
    <div class="flex flex-col gap-5 lg:gap-6">
     <div
      class="bg-white border border-[#7c868a]/30 shadow-sm p-7 md:p-8 flex flex-col rounded-lg">
      <div
       class="flex items-center gap-2 border-b border-[#7c868a]/25 pb-3 mb-5">
       <svg
        class="w-5 h-5 text-[#005b7b]"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        viewBox="0 0 24 24"
        aria-hidden="true">
        <circle cx="12" cy="12" r="9" stroke="currentColor" />
        <path
         d="M12 8v.01M11 12h1v4h1"
         stroke="currentColor"
         stroke-linecap="round"
         stroke-linejoin="round" />
       </svg>
       <h2
        class="font-inter text-base md:text-lg font-medium tracking-wide text-[#002735] uppercase">
        About this event
       </h2>
      </div>

      <p class="font-serif text-[15px] text-[#35393a] leading-relaxed mb-5">
       The <b>8th CALABARZON Research, Statistics, and Innovation (RSI) Forum</b>—themed <b>“Fostering a Resilient Tomorrow: Mobilizing Data and Digital Innovation for Sustainable Development Goal Progress, Food, Energy, and Tourism”</b>—serves as a platform for showcasing research and technological advancements. By bringing together students and professionals, the forum aims to drive regional development through collaborative knowledge sharing.
      </p>
      <p class="font-serif text-[15px] text-[#35393a] leading-relaxed mb-6">
       This event is spearheaded by the <b>CALABARZON Regional Development Council (RDC)</b> through its <b>Regional Research, Development, and Innovation Committee (RRDIC)</b>, in close collaboration with key regional partner agencies.
      </p>

      <div class="font-serif text-[15px] text-[#35393a]">
       <span class="font-inter font-medium text-[#002735] block mb-2">Objectives:</span>
       <ul
        class="list-disc pl-5 space-y-2 marker:text-[#005b7b] marker:text-sm">
        <li class="font-inter font-bold">
         Cultivate a Research-Driven Environment
         <ul class="list-[circle] pl-5">
          <li class="font-normal font-serif">To promote and sustain a robust culture of research across the CALABARZON region.</li>
         </ul>
        </li>
        <li class="font-inter font-bold">
         Facilitate Knowledge Exchange
         <ul class="list-[circle] pl-5">
          <li class="font-normal font-serif">To provide a dedicated venue for student and professional researchers to present their significant findings to a broader audience.</li>
         </ul>
        </li>
        <li class="font-inter font-bold">
         Enhance Institutional Capacity
         <ul class="list-[circle] pl-5">
          <li class="font-normal font-serif">To strengthen the research, statistical, and innovation capabilities of academic institutions, Local Government Units (LGUs), and partner government agencies.</li>
         </ul>
        </li>
       </ul>
      </div>
     </div>

     <div
      class="bg-white border border-[#7c868a]/30 shadow-sm p-7 md:p-8 flex flex-col rounded-lg">
      <div
       class="flex items-center gap-2 border-b border-[#7c868a]/25 pb-3 mb-5">
       <svg
        class="w-5 h-5 text-[#005b7b]"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        viewBox="0 0 24 24"
        aria-hidden="true">
        <rect
         x="3"
         y="4"
         width="18"
         height="16"
         rx="1"
         stroke="currentColor" />
        <path
         d="M3 10h18M9 14h6M9 18h6"
         stroke="currentColor"
         stroke-linecap="round" />
       </svg>
       <h2
        class="font-inter text-base md:text-lg font-medium tracking-wide text-[#002735] uppercase">
        Activities
       </h2>
      </div>

      <ul class="font-serif text-[15px] text-[#35393a] space-y-4 list-none">
       <li class="flex items-start gap-3">
        <span
         class="font-inter text-[#005b7b] text-xs mt-1 font-medium tracking-wider">01</span>
        <div>
         <span class="font-inter font-medium text-[#0b1214] block text-sm">Activity 1</span>
         <span class="text-[#35393a]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo quaerat, sit quasi porro aliquam aperiam mollitia quo hic soluta eum. Eius, perferendis error distinctio assumenda debitis sapiente obcaecati necessitatibus officiis.</span>
        </div>
       </li>
       <li class="flex items-start gap-3">
        <span
         class="font-inter text-[#005b7b] text-xs mt-1 font-medium tracking-wider">02</span>
        <div>
         <span class="font-inter font-medium text-[#0b1214] block text-sm">Activity 2</span>
         <span class="text-[#35393a]">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sit ea earum quae dicta blanditiis error harum illo excepturi saepe, voluptate cupiditate quis nesciunt quos natus iste ab quidem molestias? Rem.</span>
        </div>
       </li>
       <li class="flex items-start gap-3">
        <span
         class="font-inter text-[#005b7b] text-xs mt-1 font-medium tracking-wider">03</span>
        <div>
         <span class="font-inter font-medium text-[#0b1214] block text-sm">Activity 3</span>
         <span class="text-[#35393a]">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Error molestiae vitae assumenda, sint molestias perspiciatis at neque ea nesciunt debitis id numquam possimus veniam. Commodi aliquam mollitia quod! Accusamus, architecto.</span>
        </div>
       </li>
       <li class="flex items-start gap-3">
        <span
         class="font-inter text-[#005b7b] text-xs mt-1 font-medium tracking-wider">04</span>
        <div>
         <span class="font-inter font-medium text-[#0b1214] block text-sm">Activity 4</span>
         <span class="text-[#35393a]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet alias non nobis esse sapiente officia beatae explicabo illum illo, debitis velit harum sunt modi molestiae delectus aut. Porro, reprehenderit assumenda.</span>
        </div>
       </li>
       <!-- <li class="flex items-start gap-3">
        <span
         class="font-inter text-[#005b7b] text-xs mt-1 font-medium tracking-wider">06</span>
        <div>
         <span class="font-inter font-medium text-[#0b1214] block text-sm">Networking Sessions</span>
         <span class="text-[#35393a]">Structured opportunities for professional connection and
          collaboration.</span>
        </div>
       </li> -->
      </ul>
     </div>
    </div>

    <div class="w-full lg:sticky lg:top-8 lg:self-start">
     <div
      class="bg-[#002735] text-[#ebf5f8] border border-[#005b7b]/40 p-7 md:p-8 flex flex-col rounded-lg">
      <div
       class="flex items-center gap-2 border-b border-[#005b7b]/40 pb-3 mb-5">
       <svg
        class="w-5 h-5 text-[#00adec]"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        viewBox="0 0 24 24"
        aria-hidden="true">
        <path
         d="M9 12l2 2 4-4"
         stroke="currentColor"
         stroke-linecap="round"
         stroke-linejoin="round" />
        <path
         d="M12 21c4.97 0 9-4.03 9-9s-4.03-9-9-9-9 4.03-9 9 4.03 9 9 9Z"
         stroke="currentColor" />
       </svg>
       <h2
        class="font-inter text-base md:text-lg font-medium tracking-wide text-[#00adec] uppercase">
        Register
       </h2>
      </div>

      <div class="space-y-3 text-sm text-[#ebf5f8]/90 mb-6 font-serif">
       <div class="flex items-start gap-3">
        <svg
         class="w-4 h-4 text-[#00adec] mt-0.5 shrink-0"
         fill="none"
         stroke="currentColor"
         stroke-width="1.5"
         viewBox="0 0 24 24"
         aria-hidden="true">
         <rect
          x="3"
          y="5"
          width="18"
          height="16"
          rx="1"
          stroke="currentColor" />
         <path
          d="M3 9h18M8 3v4M16 3v4"
          stroke="currentColor"
          stroke-linecap="round" />
        </svg>
        <span>Oct 21, 2026<br /><span
          class="text-[#7c868a] text-xs font-inter">08:00 AM – 05:00 PM</span></span>
       </div>
       <div class="flex items-start gap-3">
        <svg
         class="w-4 h-4 text-[#00adec] mt-0.5 shrink-0"
         fill="none"
         stroke="currentColor"
         stroke-width="1.5"
         viewBox="0 0 24 24"
         aria-hidden="true">
         <path
          d="M12 21s-7-5.2-7-11a7 7 0 1 1 14 0c0 5.8-7 11-7 11Z"
          stroke="currentColor"
          stroke-linejoin="round" />
         <circle cx="12" cy="10" r="2.5" stroke="currentColor" />
        </svg>
        <span>Alangilan, Batangas City<br /><span class="text-[#7c868a] text-xs font-inter">Batangas State University</span></span>
       </div>
      </div>

      <div class="space-y-3">
       <a href="register.php"
        id="registerBtn"
        class="w-full block text-center bg-[#00adec] text-[#002735] font-inter font-medium text-sm tracking-wide py-3 px-4 border border-[#00adec] hover:bg-[#005b7b] hover:text-[#ebf5f8] hover:border-[#00adec] transition-colors duration-200 uppercase cursor-pointer">
        Register Now
       </a>

       <p class="font-inter text-[11px] text-[#7c868a] text-center">
        free admission, no account required
       </p>

       <div class="grid grid-cols-1 gap-2 pt-2">
        <button
         id="calendarBtn"
         class="font-inter bg-transparent border border-[#7c868a] text-[#ebf5f8] text-xs tracking-wide py-2 px-3 hover:border-[#00adec] hover:text-[#00adec] transition-colors duration-200 uppercase cursor-pointer">
         Add to Calendar
        </button>

        <button
         id="shareBtn"
         class="font-inter bg-transparent border border-[#7c868a] text-[#ebf5f8] text-xs tracking-wide py-2 px-3 hover:border-[#00adec] hover:text-[#00adec] transition-colors duration-200 uppercase cursor-pointer">
         Share
        </button>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </main>

 <?php include '../public/includes/footer.php' ?>

 <script src="/src/js/main.js"></script>
</body>

</html>