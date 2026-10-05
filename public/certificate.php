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
?>
<!doctype html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Certificate — 8th RSI Forum</title>
 <link rel="stylesheet" href="./css/output.css" />
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap" rel="stylesheet" />
 <style>
  .font-inter {
   font-family: "Inter", system-ui, sans-serif;
  }

  .font-serif {
   font-family: "Source Serif 4", Georgia, serif;
  }
 </style>
</head>

<body class="min-h-screen bg-[#ebf5f8] flex items-center justify-center">
 <div class="text-center">
  <p class="font-inter text-[10px] tracking-[0.35em] uppercase text-[#00adec] mb-3">Certificate</p>
  <h1 class="font-inter text-2xl font-light text-[#002735]">Coming soon</h1>
  <a href="dashboard.php" class="inline-block mt-6 font-inter text-[10px] tracking-[0.25em] uppercase text-[#005b7b] hover:text-[#00adec]">← Back to Dashboard</a>
 </div>
</body>

</html>