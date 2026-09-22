<?php
// 
// Adicionar em todas as rotas ou em arquivos que exigem a autenticação
//

// Garante que a sessão esteja aberta
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Função para destruir a sessão
function destruirSessao(): void{
  $_SESSION = []; // limpa todos os dados da sessão
  // Verifica se sessão tem cookies para armazenar
  if(ini_get("session.use_cookies")){
    $params = session_get_cookie_params();

    setcookie(
      session_name(), 
      '', 
      time() - 42000, // Define a data de expiração no passado (vence o cookie)
      $params["path"], 
      $params["domain"],
      $params["secure"], 
      $params["httponly"]
    );
  }
  session_destroy(); // destroi a sessão
}

// Validação da sessão do usuário
function validarSessao(): void{
  // Verifica se o usuário está logado
  // Não estando logado, redireciona para realizar o login
  if (!isset($_SESSION['id'])) {
    header('Location: ./');
    exit();
  }

  $maxInatividade = 3600; // tempo máximo de inatividade de 1h

  // Validação do IP e User_Agent no momento da criação do login e do momento atual (para caso de roubo de sessão)
  if(
    $_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'] ||
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']
  ){
    destruirSessao();
    header('Location: ./');
    exit();
  }

  // Se não houver tempo do último login e o tempo atual for menor do que a última ação realiza o logout
  // por tempo de inatividade
  if(isset($_SESSION['last_login']) && (time() - $_SESSION['last_login'] > $maxInatividade)){
    destruirSessao();
    header('Location: ./');
    exit();
  }

  // Atualiza o timestamp da última atividade
  $_SESSION['last_login'] = time();
}
?>