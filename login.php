<?php /* coding: utf-8 */
include './inc/header.php';
?>
<div class="min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-8">
    <!-- Logo e Cabeçalho -->
    <div class="flex flex-col items-center mb-6">
      <a href="./">
        <img src="./img/logo/icon-sigas.png" alt="Logo SIGAS" class="w-20 mb-3">
      </a>
      <h1 class="text-2xl font-bold text-gray-800">Login</h1>
      <p class="text-sm text-gray-500 mt-1">Entre com as suas credenciais</p>
    </div>
    
    <!-- Mensagem Login Status -->
    <div id="statusLogin" class=""></div>

    <!-- Formulário -->
    <form id="formLogin" class="flex flex-col">
      <!-- E-mail -->
        <label for="email">
          E-mail
        </label>
        <input 
          id="email"
          type="email"
          name="email" 
          placeholder="seu@email.com" 
          class="input"
        >
      <!-- Senha -->
        <label for="user_pwd">
          Senha
        </label>
        <input 
          type="password" 
          id="user_pwd" 
          name="user_pwd" 
          placeholder="*********" 
          class="input"
        >

      <!-- Mensagem de Erro -->
      <?php if(isset($err) && !empty($err)): ?>
        <div class="p-3 bg-red-50 border border-red-200 text-red-600 text-xs rounded-lg text-center font-medium">
          <?= $err ?>
        </div>
      <?php endif; ?>

      <!-- Botão de Envio -->
      <button type="submit" class="btn-primary" id="submitLogin">
        Entrar
      </button>
    </form>

    <!-- Link para Registro -->
    <div class="mt-6 text-center">
      <p class="text-sm font-medium">
      Não possui uma conta? Registre-se
      <a href="./register.php" class="font-medium text-blue-600 hover:text-blue-800 hover:underline transition">
        aqui.
      </a>
      </p>
    </div>
  </div>
</div>
<script src="./js/login.js"></script>
</body>
</html>