<?php /* coding: utf-8 */
include "./inc/header.php";
?>
<div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-8">
  <!-- Logo e Cabeçalho -->
  <div class="flex flex-col items-center mb-6">
    <img src="./img/logo/sigas.png" alt="Logo SIGAS" class="w-52 object-contain mb-3">
    <p data-lx="subtitle" class="text-sm text-gray-500 mt-4 text-center">Application launchpad</p>
  </div>

  <!-- Seção de Ações / Launch -->
  <div class="border border-gray-200 rounded-lg p-5 bg-gray-50/50">
    <span data-lx="launch_field" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 text-center">
      Launch
    </span>

    <div class="space-y-3">
      <!-- Botão Manager -->
      <a
        type="button"
        id="btManager"
        data-lt="manager_info"
        data-lx="manager"
        title="Launch Manager"
        href="./login.php"
		target="_blank"
        class="w-full bg-[#293F14] hover:bg-[#1f300f] text-white font-medium rounded-lg py-2.5 transition duration-150 shadow-sm flex items-center justify-center gap-2 cursor-pointer"
      >
        Manager
      </a>

      <!-- Botão E-Mail -->
      <a
        type="button"
        id="btEMail"
        data-lt="email_info"
        data-lx="email"
        title="Launch E-Mail"
        href="./email.php"
		target="_blank"
        class="w-full bg-white hover:bg-gray-50 text-[#293F14] border border-[#293F14] font-medium rounded-lg py-2.5 transition duration-150 shadow-sm flex items-center justify-center gap-2 cursor-pointer"
      >
        E-Mail
      </a>
    </div>
  </div>
</div>

<script type="text/javascript" src="./js/localisation.js"></script>
</body>
</html>