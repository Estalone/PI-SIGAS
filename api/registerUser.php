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

// Lista de tipos de usuários permitidos
$validUserTypes=["up", "uc", "ug", "if", "is", "im", "of", "os", "om", "af", "as", "am"];
if ($supermode)
	array_push($validUserTypes,"su","pd","dm");

// Recebe e limpa o input do usuário
$user_name=isset($_POST["user_name"]) ? trim($_POST["user_name"]):"";
$user_type=isset($_POST["user_type"]) ? trim($_POST["user_type"]):"";
$email=isset($_POST["email"]) ? trim($_POST["email"]):"";
$user_pwd=isset($_POST["user_pwd"]) ? trim($_POST["user_pwd"]):"";
$user_repwd=isset($_POST["user_repwd"]) ? trim($_POST["user_repwd"]):"";

$errors=[];

// Validação dos campos
if (empty($user_name)){
    $errors["user_name"]="O nome é obrigatório.";
}

$user_name = htmlspecialchars($user_name, ENT_QUOTES, "UTF-8");

if (empty($user_type)){
    $errors["user_type"] = "Selecione o tipo de usuário.";
}elseif(!in_array($user_type, $validUserTypes, true)){
    $errors["user_type"] = "Tipo de usuário inválido.";
}

if (empty($email)) {
    $errors["email"] = "E-Mail é obrigatório.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Informe um endereço de E-Mail válido.";
}

if (empty($user_pwd)) {
    $errors["user_pwd"] = "A senha é obrigatória.";
}

if (empty($user_repwd)) {
    $errors["user_repwd"] = "A confirmação de senha é obrigatória.";
}

if($user_repwd !== $user_pwd){
  $errors["user_repwd"] = "As senhas não coincidem.";
}

if(!empty($errors)){
  // Une todas as menssagens de erro
  $mensagemErro = implode(" ", $errors);

  http_response_code(400);
    echo json_encode([
        "success" => false,
        "mensagem" => "Verifique os dados informados: ".$mensagemErro
    ]);
    exit;
}

// Criptografa a senha
function genToken($length){
	$date=new DateTimeImmutable("now",new DateTimeZone("UTC"));
	$token=$date->format("YmdHis");
	$alphabet="ABCFHIJKLNTVXZ";

	$a=($length>strlen($token))?$length-strlen($token):0;

	while($a--)
		$token.=substr($alphabet,rand(0,13),1);

	return $token;
}

$senhaHash = password_hash($user_pwd, PASSWORD_DEFAULT);
$token=genToken(16);
$status=0;

// Prepara e executa a inserção com PDO
try{
	// Verifica se o usuário ou E-Mail já estão cadastrados na base
	$checkSQL="SELECT id FROM users WHERE name=:user_name OR email=:email";
	$pdo->prepare($checkSQL);
	$checkStmt->execute([
		":user_name"	=> $user_name,
		":email"		=> $email
	]);

  if($checkStmt->fetch()){
    http_response_code(400);
    echo json_encode([
        "success"  => false,
        "mensagem" => "O usuário e/ou o E-Mail informado já está cadastrado no sistema."
    ]);
    exit();
  }

	// Não existindo o usuário, nem o E-Mail, cria o usuário
	$sql="INSERT INTO users (name,password,email,type,status,token) VALUES (:name,:password,:email,:type,:status,:token)";
	$stmt=$pdo->prepare($sql);
	$result=$stmt->execute([
		":name"		=> $user_name,
		":password"	=> $senhaHash,
		":email"	=> $email,
		":type"		=> $user_type,
		":status"	=> $status,
		":token"	=> $token
	]);
	$sender="no-reply@sigas.ibama.gov.br";
	$confirm_msg="Use este token para ativar a conta: ".$token."</br>\n</br>\nOu <a href=\"https://pi2g1.freedev.app/confirm.php?token=".$token."&email=".$email."\" target=\"_blank\">clique aqui para ativar</a>!";

	if($result){
		http_response_code(201);
		echo json_encode([
			"success"	=> true,
			"mensagem"	=> "Usuário cadastrado com sucesso!"
		]);
		sendEMail($pdo,$sender,$email,"SIGAS - Registration confirmation",$confirm_msg);
		exit();
	}
}catch(PDOException $e){
	http_response_code(500);
	echo json_encode(["success" => false, "error" => "Erro interno no servidor."]);
	exit();
}
?>
