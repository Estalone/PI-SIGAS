<?php /* coding: utf-8 */
include __DIR__."/inc/header.php";

// Validação da sessão
require_once __DIR__.'/inc/validAuth.php';

// Executa a verificação no início da requisição
validarSessao();

// Função para exibir as iniciais do nome
function initialsName($name){
	echo preg_replace("/^([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*.*$/","$1$2$3$4$5$6$7$8$9",$name);
}

function firstName($name){
	echo preg_replace("/^([^\s]+).*$/","$1",$name);
}

?>
<nav class="w-full bg-white p-5 drop-shadow">
	<div class="container-lg mx-auto w-[1280px] max-md:w-full shadown">
		<span class="material-symbols-outlined text-stone-500">menu</span>
<table border="0" cellspacing="0" cellpadding="8" width="90%">
	<tr>
		<td width="25%"><form action="./account.php">
			<button class="btn-primary" type="submit" data-lt="ed_account" title="Editar conta." data-lv="bt_ed_account">Editar conta</button>
		</form></td>
		<td width="25%"><form action="./livestock.php">
			<button class="btn-primary" type="submit" data-lt="mne_livestock" title="Gerenciar plantel." data-lv="bt_mng_livestock">Gerenciar Plantel</button>
		</form></td>
		<td width="25%"><br></td>
		<td width="25%"><br></td>
	</tr>
</table>
    </div>
</nav>

	<h1 data-lx="title">SIGAS - Sistema Integrado de Gestão de Animais Silvestres</h1>
	<h2>Login</h2>
	<h3>Welcome, <?php firstName($_SESSION['name']); ?>!</h3>
	<hr>
	<h4>Your Account Details:</h4>
	<dl>
		<dt>Id</dt>
			<dd><?php echo $_SESSION['id']; ?></dd>
		<dt>User Name</dt>
			<dd><?php echo $_SESSION['name']; ?></dd>
	</dl>

	<form action="./logout.php">
		<input class="Submit" type="submit" title="Log out" value="Log out"/>
	</form>
	<script type="text/javascript" src="./localisation.js"></script>
</body>
</html>
