<?php 
include './inc/header.php';

// Itens para ser exibido no select
$selectUserType=[
    ["value" => "", "label" => "-- Selecione --"],
    ["value" => "up", "label" => "Usuário (pessoa física)"],
    ["value" => "uc", "label" => "Empresa privada (pessoa jurídica)"],
    ["value" => "ug", "label" => "Órgão público (pessoa jurídica)"],
    ["value" => "if", "label" => "Fiscal Federal"],
    ["value" => "is", "label" => "Fiscal Estadual"],
    ["value" => "im", "label" => "Fiscal Municipal"],
    ["value" => "of", "label" => "Operador Federal"],
    ["value" => "os", "label" => "Operador Estadual"],
    ["value" => "om", "label" => "Operador Municipal"],
    ["value" => "af", "label" => "Administrador Federal"],
    ["value" => "as", "label" => "Administrador Estadual"],
    ["value" => "am", "label" => "Administrador Municipal"]
];
if ($supermode)
	array_push($selectUserType,
		["value" => "su", "label" => "Superusuário"],
		["value" => "pd", "label" => "Desenvolvedor"],
		["value" => "dm", "label" => "Gerente da base de dados"]);
?>
<div class="min-h-screen flex items-center justify-center p-4">
	<div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 my-8">
      <!-- Logo e Cabeçalho -->
      <div class="flex flex-col items-center mb-6">
        <a href="./">
          <img src="./img/logo/icon-sigas.png" alt="Logo SIGAS" class="w-20 mb-3">
        </a>
        <h1 data-lx="title" class="text-2xl font-bold text-gray-800 text-center">Registrar</h1>
        <h2 data-lx="subtitle" class="text-sm text-gray-500 mt-1 text-center">Crie uma nova conta.</h2>
      </div>

      <!-- Mensagem Registro Status -->
      <div id="statusRegister" class=""></div>

      <form id="formRegister" class="flex flex-col">
        <!-- User Name -->
        <label for="user_name" data-lt="unique_unanme" data-lx="uname" title="Enter a unique user_name">
          Nome do usuário
        </label>
        <input 
          type="text" 
          name="user_name" 
          placeholder="Digite seu nome" 
          class="input"
        />
        <!-- User Type -->
        <label for="user_type">
          Tipo de usuário
        </label>
        <select 
          name="user_type" 
          class="input"
        >
        <?php foreach ($selectUserType as $option): ?>
          <option value="<?= $option['value'] ?>">
            <?= htmlspecialchars($option['label']) ?>
          </option>
        <?php endforeach; ?>
        </select>
        <!-- E-Mail -->
        <label for="user_email">
          E-Mail
        </label>
        <input 
          type="email" 
          name="email" 
          placeholder="Endereço de E-mail" 
          class="input"
        />
        <!-- Password -->
        <label for="user_pwd">
          Senha
        </label>
        <input  
          type="password" 
          name="user_pwd" 
          placeholder="********" 
          class="input"
        />
        <!-- Password Again -->
        <label for="user_repwd" >
          Confirme senha
        </label>
        <input 
          type="password" 
          name="user_repwd" 
          title="Enter password again" 
          placeholder="********" 
          class="input"
        />
        <!-- Botão de Envio -->
        <button
          type="submit" 
          class="btn-primary"
          id="submitRegister"
        >
          Criar conta
        </button>
      </form>

      <!-- Link para login -->
      <div class="mt-6 text-center text-sm">
        <p class="text-sm font-medium">
          Tem uma conta? Faça o login
          <a href="./login.php" class="font-medium text-blue-600 hover:text-blue-800 hover:underline transition">
            aqui.
          </a>
        </p>
      </div>
    </div>
  </div>
	<script src="./js/registerUser.js"></script>
	<script src="./js/localisation.js"></script>
</body>
</html>
