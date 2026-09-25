<?php
// Configura o cabeçalho para sempre responder em JSON
header("Content-Type: application/json; charset=utf-8");

// Conexão com o banco
require_once __DIR__."/../inc/connection.php";

// Bloqueia chamadas via GET
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(["error" => "Método não permitido."]);
  exit();
}

// Recebe e limpa o input do usuário
$email=isset($_POST["email"]) ? trim($_POST["email"]):"";
$token=isset($_POST["token"]) ? trim($_POST["token"]):"";

$errors=[];

// Validação dos campos
if (empty($token)){
	http_response_code(400);
	$errors["token"]="O token é obrigatório.";
}

$token=htmlspecialchars($token,ENT_QUOTES,"UTF-8");

if (empty($email)) {
	http_response_code(400);
	$errors["email"] = "E-Mail é obrigatório.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	http_response_code(400);
	$errors["email"] = "Informe um endereço de E-Mail válido.";
}

if(!empty($errors)){
	// Une todas as menssagens de erro
	$mensagemErro = implode(" ", $errors);

	echo json_encode([
		"success" => false,
		"mensagem" => "Verifique os dados informados: ".$mensagemErro
	]);
	exit;
}

// Prepara e executa a inserção com PDO
try{
	// Atualiza status do usuário para ativo e apaga o token usado.
	$sql="UPDATE users SET status=1,token=NULL WHERE email=:email AND token=:token AND status=0";
	$stmt=$pdo->prepare($sql);
	$result=$stmt->execute([
		"email"=>$email,
		"token"=>$token
	]);

	if($result->numrows>0){
		http_response_code(201);
		echo json_encode([
			"success"	=> true,
			"mensagem"	=> "Conta confirmada com sucesso!"
		]);
		exit();
	}else{
		http_response_code(406);
		echo json_encode([
			"success"	=> false,
			"mensagem"	=> "Token inválido ou E-Mail não encontrado! Verifique os dados, se a conta já está ativa ou tente ativar novamente."
		]);
		exit();
    }
}catch(PDOException $e){
	http_response_code(500);
	echo json_encode(["success" => false, "error" => "Erro interno no servidor."]);
	exit();
}
?>
