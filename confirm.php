<?php /* coding: utf-8 */
include "./inc/header.php";

// Bloqueia chamadas via GET
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	http_response_code(405);
	echo json_encode(["error" => "Método não permitido."]);
	exit();
}

if (empty($token)) {
	$mensagemErro="O token é obrigatório.";
	http_response_code(400);
	echo json_encode([
		"success" => false,
		"mensagem" => "Verifique os dados informados: " . $mensagemErro,
	]);
	exit;
}

$token=htmlspecialchars($token,ENT_QUOTES,"UTF-8");

try {
	// Search for the token and activate account.
	$checkSQL = "UPDATE users SET token=NULL, status=1 WHERE token=:token AND status=0";
	$checkStmt=$pdo->prepare($checkSQL);
	$result=$checkStmt->execute([":token"=>$token]);

	if ($result){
		http_response_code(201);
		echo json_encode([
			"success"	=> true,
			"mensagem"	=> "Conta ativada com sucesso!",
		]);
	exit();
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=0.9">
<title data-lx="title">Account Activation</title>
</head>
<body>
	<h1 data-lx="title">Account Activation</h1>
	<h2 data-lx="subtitle">Activate an account</h2>
	<h3 data-lx="info">Before accessing an account, it must be activated using the token sent to the registered E-Mail address.</h3>
	<hr>
<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;margin:auto">
	<tr>
		<td><label for="Token" title="Enter the token.">Token:</label> <input type="text" id="Token" name="Token" value="" placeholder="Enter the token." title="Enter the token." size="20" autofocus required/></td>
	</tr>
</table>
<?php
	$conn->close();
?>
</body>
</html>
