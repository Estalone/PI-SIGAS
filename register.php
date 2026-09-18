<?php /* coding: utf-8 */
include "./header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
	<link rel="stylesheet" href="./css/main.css">
	<title data-lx="title">SIGAS - User Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<?php
if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    //cria as variáveis
    $user_name   = isset($_POST["user_name"]) ? trim($_POST["user_name"]) : "";
    $user_pwd    = isset($_POST["user_pwd"]) ? $_POST["user_pwd"] : "";
    $user_repwd  = isset($_POST["user_repwd"]) ? $_POST["user_repwd"] : "";
    $user_email  = isset($_POST["user_email"]) ? trim($_POST["user_email"]) : "";
    $user_type   = isset($_POST["user_type"]) ? trim($_POST["user_type"]) : "";
    $user_status = 0; //status inicial da confirmação da conta
    $user_token  = GRT(18); // variável para o token

    // validação simples de campos nulos
    if (empty($user_name) || empty($user_pwd) || empty($user_email) || empty($user_type)) {
        $err = "All fields are required.";
    } elseif ($user_pwd !== $user_repwd) {
        $err = "Passwords do not match.";
    } else {
        // Hash da senha
        $user_pwd_hash = password_hash($user_pwd, PASSWORD_DEFAULT);

        // Verifica email duplicados
        $sql = "SELECT user_name, user_email FROM t_users WHERE user_name = ? OR user_email = ?";
        
        //caso o supermode estiver ativo adiciona a checagem
        if ($supermode && "dm" === $user_type) {
            $sql .= " OR user_type = ?";
        }
		
        // retorna true se a sintaxe estiver correta
        if ($stmt = $conn->prepare($sql)) {
            if ($supermode && "dm" === $user_type) {
                $stmt->bind_param("sss", $user_name, $user_email, $user_type);
            } else {
                $stmt->bind_param("ss", $user_name, $user_email);
            }
			
            //executa a consulta e armazena os resultados
            $stmt->execute();
            $result = $stmt->get_result();
			
            // verificação de duplicados
            $check = 0;	
            $check_name = 0;
            $check_email = 0;
			
            while ($row = $result->fetch_assoc()) {
                $check++;
                if ($user_name === $row["user_name"]) $check_name++;
                if ($user_email === $row["user_email"]) $check_email++;
            }
            
            $stmt->close();
			
            // existindo um parametro identico retorna erro
            if ($check > 0) {
                if ($supermode) {
                    if ("dm" === $user_type) $err = "Error: there can be only one DM!";
                    elseif ($check_name > 0) $err = "User name is already taken!";
                    elseif ($check_email > 0) $err = "E-Mail is already registered!";
                } else {
                    $err = "User name or E-Mail is already registered!";
                }
            } else {
                // estando tudo certo adiciona o novo usuário
                $sql_ins = "INSERT INTO `t_users` (`user_name`,`user_type`,`user_email`,`user_pwd`,`user_status`,`user_token`) VALUES (?,?,?,?,?,?)";
               	
                // retorna true se a sintaxe estiver correta
                if ($stmt_ins = $conn->prepare($sql_ins)) {
                    $stmt_ins->bind_param("ssssis", $user_name, $user_type, $user_email, $user_pwd_hash, $user_status, $user_token);
                    $stmt_ins->execute();
					
                    //existindo o usuário foi incluído com sucesso
                    if ($stmt_ins->affected_rows > 0) {
                        //
                        // verificar a possibilidade de envio de email
                        //
                        $_SESSION["success_msg"] = "Account has been created! Check your E-Mail to activate it.";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        $err = "Creating your account has failed!";
                    }
                    
                    $stmt_ins->close();
                }
            }
        }
    }
}
?>
	<div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 my-8">
        <!-- Logo e Cabeçalho -->
        <div class="flex flex-col items-center mb-6">
            <a href="./">
                <img src="./img/logo/icon-sigas.png" alt="Logo SIGAS" class="w-20 mb-3">
            </a>
            <h1 data-lx="title" class="text-2xl font-bold text-gray-800 text-center">User Registration</h1>
            <h2 data-lx="subtitle" class="text-sm text-gray-500 mt-1 text-center">Create new account.</h2>
        </div>

        <form id="registration" method="POST" onsubmit="return checkPasswords()" class="space-y-4">
            
            <!-- User Name -->
            <div class="flex flex-col space-y-1">
                <label for="user_name" title="Enter a unique user_name" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    User name
                </label>
                <input 
                    required 
                    type="text" 
                    id="user_name" 
                    name="user_name" 
                    title="Enter a unique user name" 
                    placeholder="unique user name" 
                    value="<?=isset($_POST["user_name"])?safeInput($_POST["user_name"]):""?>" 
                    class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition bg-gray-100"
                />
            </div>

            <!-- User Type -->
            <div class="flex flex-col space-y-1">
                <label for="user_type" title="Select user type" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    User type
                </label>
                <select 
                    required 
                    id="user_type" 
                    name="user_type" 
                    title="Select user type" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition"
                >
                    <option value="up"<?=IOS(USER_TP_UP,$user_type)?>>Usuário (pessoa física)</option>
                    <option value="uc"<?=IOS(USER_TP_UC,$user_type)?>>Empresa privada (pessoa jurídica)</option>
                    <option value="ug"<?=IOS(USER_TP_UG,$user_type)?>>Órgão público (pessoa jurídica)</option>
                    <option value="if"<?=IOS(USER_TP_IF,$user_type)?>>Fiscal Federal</option>
                    <option value="is"<?=IOS(USER_TP_IS,$user_type)?>>Fiscal Estadual</option>
                    <option value="im"<?=IOS(USER_TP_IM,$user_type)?>>Fiscal Municipal</option>
                    <option value="of"<?=IOS(USER_TP_OF,$user_type)?>>Operador Federal</option>
                    <option value="os"<?=IOS(USER_TP_OS,$user_type)?>>Operador Estadual</option>
                    <option value="om"<?=IOS(USER_TP_OM,$user_type)?>>Operador Municipal</option>
                    <option value="af"<?=IOS(USER_TP_AF,$user_type)?>>Administrador Federal</option>
                    <option value="as"<?=IOS(USER_TP_AS,$user_type)?>>Administrador Estadual</option>
                    <option value="am"<?=IOS(USER_TP_AM,$user_type)?>>Administrador Municipal</option>
                    <?php
                        if ($supermode){
                            echo "<option value=\"su\"".IOS(USER_TP_SU,$user_type).">Super User</option>\n";
                            echo "<option value=\"pd\"".IOS(USER_TP_PD,$user_type).">Programming Developer</option>\n";
                            echo "<option value=\"dm\"".IOS(USER_TP_DM,$user_type).">Database Manager</option>\n";
                        }
                    ?>
                </select>
            </div>

            <!-- E-Mail -->
            <div class="flex flex-col space-y-1">
                <label for="user_email" title="Enter E-Mail" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    E-Mail
                </label>
                <input 
                    type="email" 
                    id="user_email" 
                    name="user_email" 
                    value="<?=isset($_POST["user_email"])?safeInput($_POST["user_email"]):""?>" 
                    title="Enter E-Mail address" 
                    placeholder="E-Mail address" 
                    class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition bg-gray-100"
                />
            </div>

            <!-- Password -->
            <div class="flex flex-col space-y-1">
                <label for="user_pwd" title="Enter password" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Password
                </label>
                <input 
                    required 
                    type="password" 
                    id="user_pwd" 
                    name="user_pwd" 
                    title="Enter password" 
                    placeholder="********" 
                    class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition bg-gray-100"
                />
            </div>

            <!-- Password Again -->
            <div class="flex flex-col space-y-1">
                <label for="user_repwd" title="Enter password again" class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Password again
                </label>
                <input 
                    required 
                    type="password" 
                    id="user_repwd" 
                    name="user_repwd" 
                    title="Enter password again" 
                    placeholder="********" 
                    class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#293F14] focus:border-transparent transition bg-gray-100"
                />
            </div>

            <!-- Mensagem de Erro do PHP -->
            <?php if (isset($err) && !empty($err)): ?>
                <div class="p-3 bg-red-50 border border-red-200 text-red-600 text-xs rounded-lg text-center font-medium">
                    <?= $err ?>
                </div>
            <?php endif; ?>

            <!-- Botão de Envio -->
            <button 
                type="submit" 
                title="Click to register new user" 
                class="w-full bg-[#293F14] hover:bg-[#1f300f] text-white font-medium rounded-lg py-2.5 transition duration-150 shadow-sm mt-2 cursor-pointer"
            >
                Create Account
            </button>
        </form>

        <!-- Mensagem de Sucesso / Link de Navegação -->
        <div class="mt-6 text-center">
            <?php if (isset($_SESSION["success_msg"]) && !empty($_SESSION["success_msg"])): ?>
                <div class="p-3 bg-green-50 border border-green-200 text-green-700 text-xs rounded-lg font-medium mb-3">
                    <?= $_SESSION["success_msg"] ?>
                </div>
                <?php unset($_SESSION["success_msg"]); ?>
            <?php endif; ?>
            
            <p class="text-sm font-medium">
                Already have an account? <a href="/login.php" class="text-blue-600 hover:text-blue-800 hover:underline transition">Login here</a>
            </p>
        </div>
    </div>
	<?php
		$conn->close();
	?>
<script>
	function checkPasswords(){
        // verifica se as senhas estão iguais
        const pwd = document.getElementById("user_pwd").value;
        const repwd = document.getElementById("user_repwd").value;
        
		if (pwd !== repwd){
			event.preventDefault();
            alert("Passwords do not match");
		}
	}
</script>
</body>
</html>