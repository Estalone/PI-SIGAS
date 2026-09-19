<?php
// Configura o cabeçalho para sempre responder em JSON
header('Content-Type: application/json; charset=utf-8');

// Conexão com o banco
require_once __DIR__ . '/../../seguro/sigas/connection.php';

// Bloqueia chamadas via GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Método não permitido.']);
  exit();
}

session_start();

// Recebe e limpa o input do usuário
$user_email = isset($_POST['email']) ? trim($_POST['email']) : '';

try {
  // Prepara e executa a consulta com PDO
  $sql = "SELECT * FROM `t_users` WHERE `user_email` = :email LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['email' =>$user_email]);

  $usuario =$stmt->fetch(PDO::FETCH_ASSOC); // Busca o usuário como array associativo

  if ($usuario) {
    // Verifica a senha digitada com o hash salvo no banco
    if (password_verify($user_pwd,$usuario['user_pwd'])) {
      
    // Salva as informações do usuário na sessão
      foreach ($usuario as $key =>$value) {
        // Não salva o hash da senha na sessão
        if ($key !== 'user_pwd') {
          $_SESSION[$key] =$value;
        }
      }

      echo json_encode([
          'success' => true,
          'message' => 'Login realizado com sucesso!',
      ]);
      exit();
    }

    // Senha incorreta
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuário ou senha inválidos.']);
    exit();
  }

  // Usuário não encontrado
  http_response_code(401);
  echo json_encode(['success' => false, 'error' => 'Usuário ou senha inválidos.']);
  exit();

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => 'Erro interno no servidor.']);
  exit();
}
?>