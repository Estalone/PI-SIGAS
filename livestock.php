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

function initialsName($name){
	echo preg_replace("/^([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*([^\s])[^\s]*\s*.*$/","$1$2$3$4$5$6$7$8$9",$name);
}
?>
<table border="0" cellspacing="0" cellpadding="8" width="90%">
	<tr>
        <td width="12%"><img src="./img/logo/icon-sigas.png" alt="Logo SIGAS" class="w-20 mb-3"></td>
	</tr>
	<tr>
		<td width="22%"><form action="./home.php">
			<button class="btn-primary" type="submit" data-lt="home" title="Ir para a tela principal." data-lv="bt_ed_account">Dashboard</button>
		</form></td>
		<td width="22%"><form action="./livestock.php">
			<button class="btn-primary" type="submit" data-lt="mne_livestock" title="Gerenciar plantel." data-lv="bt_mng_livestock">Gerenciar Plantel</button>
		</form></td>
		<td width="22%"></td>
		<td width="22%">
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
                    <td>Usuário:</td><td><?= initialsName($_SESSION["name"]) ?></td>
				</tr>
				<tr>
					<td>Tipo:</td><td><?= ($_SESSION["name"]) ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

        <h1 data-lx="title" class="text-2xl font-bold text-gray-800 text-center">Gerenciar Conta</h1>
        <h2 data-lx="subtitle" class="text-sm text-gray-500 mt-1 text-center">Altere dados da conta.</h2>

	<!-- Mensagem Conta Status -->
	<div id="statusLivestock" class=""></div>

<form><table border="0" cellspacing="0" cellpadding="2" width="90%">
	<tr>
		<th>Id</th>
		<th>Espécie</th>
		<th>Vernáculo</th>
		<th>Sexo</th>
		<th>Marcação</th>
		<th>Recinto</th>
		<th>Proprietário</th>
		<th>Empreendimento</th>
		<th>Status</th>
		<th>Ações</th>
	</tr>
	<tr>
		<td><input type="text" data-lt="animal_id" title="Código do animal." disabled/></td>
		<td><input type="text" data-lt="animal_id" title="Nome científico."/></td>
		<td><input type="text" data-lt="animal_id" title="Nome vernacular/popular."/></td>
		<td><select id="Sex_" name="Sex_" placeholder="Sexo" title="Sexo do animal." size="1" class="S" required>
			<option value="i" selected title="indefinido">i</option>
			<option value="f" title="fêmea">f</option>
			<option value="m" title="macho">m</option>
			<option value="h" title="hermafrodita">h</option>
		</select></td>
		<td><input type="text" data-lt="animal_id" title="Marcação do animal."/></td>
		<td><input type="text" data-lt="animal_id" title="Recinto onde o animal está alojado."/></td>
		<td><input type="text" data-lt="animal_id" title="Proprietário."/></td>
		<td><input type="text" data-lt="animal_id" title="Empreendimento."/></td>
		<td><input type="text" data-lt="animal_id" title="Status do animal."/></td>
		<td style="white-space:nowrap">
			<input type="button" data-lt="upd_animal" title="Cadastrar/atualizar animal." value="+" style="padding:2px;border:2px solid black">&nbsp;<input type="button" data-lt="dest_animal" title="Destinar animal." value="&minus;" style="padding:2px;border:2px solid black">&nbsp;<input type="button" data-lt="undo_all" title="Desfazer alterações." value="↶" style="padding:2px;border:2px solid black">
		</td>
	</tr>
</table></form>

<script src="./js/livestockUpdate.js"></script>
<script src="./js/localisation.js"></script>
</body>
</html>
