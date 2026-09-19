<?php
include_once "../connection.php";

// Bloqueia chamadas via GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Método não permitido.']);
  exit();
}

// Valida se a requisição veio via Fetch / AJAX
$isFetch = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (!$isFetch) {
  http_response_code(403);
  echo json_encode(['error' => 'Acesso direto não permitido.']);
  exit();
}

// Recebe e limpa o input do usuário
$user_email = isset($_POST['email']) ? trim($_POST['email']) : '';

// prepara e consulta se existe uma conta com esse email
$sql = "SELECT * FROM `t_users` WHERE `user_email` = ?";

$stmt =$conn->prepare($sql);$stmt->bind_param("s", $user_email);$stmt->execute();


$result =$stmt->get_result();
if($result->num_rows > 0){
// If user data exist
$details =$result->fetch_assoc();
// verify given password
$password_verify = password_verify($_POST['user_pwd'],$details['user_pwd']);
if($password_verify){
    // Save user details on session
    foreach($details as$k => $v){$_SESSION[$k] =$v;
    }
    header('location:./index.php');
}else{
    // If Password does not match
    $err = "Invalid match of user name and password.";
}
}else{
// If User details does not exist
$err = "Invalid user name.";
}

?>