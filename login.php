<?php /* coding: utf-8 */
include(__DIR__ . '/inc/header.php');

// Process Login
if($_SERVER['REQUEST_METHOD'] == "POST"){
  // User name
  $user_name = addslashes($conn->real_escape_string($_POST['user_name']));
  // Check if user exists
  $sql = "SELECT * FROM `t_users` where `user_name` = ?";
  $stmt =$conn->prepare($sql);$stmt->bind_param("s", $user_name);$stmt->execute();
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
}
?>
<div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-8">
  <!-- Logo e Cabeçalho -->
  <div class="flex flex-col items-center mb-6">
    <a href="./">
      <img src="./img/logo/icon-sigas.png" alt="Logo SIGAS" class="w-20 mb-3">
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Login</h1>
    <p class="text-sm text-gray-500 mt-1">Enter your credentials.</p>
  </div>
  <form action="./login.php" method="POST" class="space-y-4">
    <!-- Usuário -->
    <div class="flex flex-col space-y-1">
      <label for="user_name" data-lx="user_name" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
        User name
      </label>
      <input 
        required 
        type="text" 
        id="user_name" 
        name="user_name" 
        data-lt="user_name_info" 
        data-lp="user_name_ph" 
        value="<?= isset($_POST['user_name']) ?$_POST['user_name'] : '' ?>" 
        placeholder="Enter user name" 
        class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition bg-gray-100"
      >
    </div>

    <!-- Senha -->
    <div class="flex flex-col space-y-1">
      <label for="user_pwd" data-lx="user_pwd" title="Enter your registered password" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
        Password
      </label>
      <input 
        required 
        type="password" 
        id="user_pwd" 
        name="user_pwd" 
        data-lt="user_pwd_info" 
        data-lp="user_pwd_ph" 
        title="Enter your registered password" 
        placeholder="Enter password" 
        class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition bg-gray-100"
      >
    </div>

    <!-- Mensagem de Erro -->
    <?php if(isset($err) && !empty($err)): ?>
      <div class="p-3 bg-red-50 border border-red-200 text-red-600 text-xs rounded-lg text-center font-medium">
        <?= $err ?>
      </div>
    <?php endif; ?>

    <!-- Botão de Envio -->
    <button type="submit" class="w-full bg-[#293F14] hover:bg-[#1f300f] text-white font-medium rounded-lg py-2.5 transition duration-150 shadow-sm mt-2">
      Log in
    </button>
  </form>

  <!-- Link para Registro -->
  <div class="mt-6 text-center">
    <a href="./register.php" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline transition">
      Register new user
    </a>
  </div>
</div>
<?php
  $conn->close();
?>
</body>
</html>