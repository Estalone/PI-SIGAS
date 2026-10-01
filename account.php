<?php
include __DIR__."/inc/header.php";

// Itens para ser exibido no select
$userType=[
	"up"=>"Usuário (pessoa física)",
	"uc"=>"Empresa privada (pessoa jurídica)",
	"ug"=>"Órgão público (pessoa jurídica)",
	"if"=>"Fiscal Federal",
	"is"=>"Fiscal Estadual",
	"im"=>"Fiscal Municipal",
	"of"=>"Operador Federal",
	"os"=>"Operador Estadual",
	"om"=>"Operador Municipal",
	"af"=>"Administrador Federal",
	"as"=>"Administrador Estadual",
	"am"=>"Administrador Municipal",
	"su"=>"Superusuário",
	"pd"=>"Desenvolvedor",
	"dm"=>"Gerente da base de dados"
];
?>
<table border="0" cellspacing="0" cellpadding="8" width="90%">
	<tr>
		<td width="25%"><form action="./home.php">
			<button class="btn-primary" type="submit" data-lt="home" title="Ir para a tela principal." data-lv="bt_ed_account">Dashboard</button>
		</form></td>
		<td width="25%"><form action="./livestock.php">
			<button class="btn-primary" type="submit" data-lt="mne_livestock" title="Gerenciar plantel." data-lv="bt_mng_livestock">Gerenciar Plantel</button>
		</form></td>
		<td width="25%"><br></td>
		<td width="25%"><br></td>
	</tr>
</table>
<div class="min-h-screen flex items-center justify-center p-4">
	<div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 my-8">
      <!-- Logo e Cabeçalho -->
      <div class="flex flex-col items-center mb-6">
        <a href="./">
          <img src="./img/logo/icon-sigas.png" alt="Logo SIGAS" class="w-20 mb-3">
        </a>
        <h1 data-lx="title" class="text-2xl font-bold text-gray-800 text-center">Gerenciar Conta</h1>
        <h2 data-lx="subtitle" class="text-sm text-gray-500 mt-1 text-center">Altere dados da conta.</h2>
      </div>

	<!-- Mensagem Conta Status -->
	<div id="statusAccount" class=""></div>

	<form id="formAccount" class="flex flex-col">
		<!-- User Name -->
		<label for="user_name" data-lt="unique_unanme" data-lx="uname" title="Enter a unique user_name">
			Nome do usuário
		</label>
        <input 
			type="text" 
			placeholder="Nome do usuário cadastrado" 
			class="input"
			disabled
			value="<?= $_SESSION["name"] ?>"
		/>
		<!-- User Type -->
		<label for="user_type">
			Tipo de usuário
		</label>
		<input
			type="text" 
			name="user_type" 
			class="input"
			disabled
			value="<?= $userType[$_SESSION["type"]] ?>"
		>
		<!-- E-Mail -->
		<label for="user_email">
			E-Mail
		</label>
		<input 
			type="email" 
			name="email" 
			placeholder="Endereço de E-mail" 
			class="input"
			value="<?= $_SESSION["email"] ?>"
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
				id="submitUpdate"
			>
				Atualizar dados
			</button>
		</form>
	</div>
</div>
<script src="./js/accountUpdate.js"></script>
<script src="./js/localisation.js"></script>
</body>
</html>