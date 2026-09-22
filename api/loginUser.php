<?php
// Configura o cabeçalho para sempre responder em JSON
header('Content-Type: application/json; charset=utf-8');

// Conexão com o banco
require_once __DIR__.'/../inc/connection.php';

// Bloqueia chamadas via GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Método não permitido.']);
  exit();
}

// Recebe e limpa o input do usuário
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$user_pwd= isset($_POST['user_pwd']) ? trim($_POST['user_pwd']) : '';

$errors = [];

// Validação do e-mail
if (empty($email)) {
    $errors['email'] = "Email é obrigatório.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Informe um e-mail válido.";
}

// Validação dos campos
if (empty($user_pwd)) {
    $errors['user_pwd'] = "Senha é obrigatória.";
}

if(!empty($errors)){
  // Une todas as menssagens de erro
  $mensagemErro = implode(' ', $errors);

  http_response_code(400);
    echo json_encode([
        'success' => false,
        'mensagem' => 'Verifique os dados informados: ' . $mensagemErro,
    ]);
    exit;
}

$isProduction = $_ENV['APP_ENV'] === 'production'; // verifica o local de desenvolvicmento, sendo em produção exige o HTTPS

session_set_cookie_params([
    'lifetime' => 0,             // O cookie expira assim que o navegador é fechado
    'path' => '/',                // Válido em todo o domínio
    'domain' => '',
    'secure' => $isProduction,
    'httponly' => true,          // Impede acesso ao cookie via JavaScript (protege contra XSS)
    'samesite' => 'Lax'
]);

ini_set('session.use_only_cookies', 1); // Força a usar apenas cookies (evita passar ID de sessão na URL)
ini_set('session.use_strict_mode', 1);  // Impede que o servidor aceite IDs de sessão arbitrários

session_start();

// Prepara e executa a consulta com PDO
try {
  $sql = "SELECT * FROM `users` WHERE `email` = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['email' => $email]);

  $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

  // Verifica se existe o usuário e se a senha digitada com o hash salvo no banco está correta
  if ($usuario && password_verify($user_pwd, $usuario['password'])) {

      session_regenerate_id(true);

      // Salva os dados do usuário na sessão
      $_SESSION['id'] = $usuario['id'];
      $_SESSION['name'] = $usuario['name'];
      $_SESSION['last_login'] = time();
      $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
      $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

      http_response_code(200);
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
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => 'Erro interno no servidor.']);
  exit();
}
?>