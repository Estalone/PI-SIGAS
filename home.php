<?php /* coding: utf-8 */
include "./inc/header.php";

// Validação da sessão
require_once __DIR__ . '/inc/validAuth.php';

// Executa a verificação no início da requisição
validarSessao();

// Função para exibir as iniciais do nome
function iniciaisName($name){
	echo $name;
}

?>
  <nav class="w-full bg-white p-5 drop-shadow">
    <div class="container-lg mx-auto w-[1280px] max-md:w-full shadown">
      <span class="material-symbols-outlined text-stone-500">menu</span>
    </div>
  </nav>
	<h1>Yaguara</h1>
	<h2>Login</h2>
	<h3>Welcome, <?php iniciaisName($_SESSION['name']); ?>!</h3>
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