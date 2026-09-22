<?php
// Carrega as informações de autenticação do banco via .env
require_once __DIR__.'/../vendor/autoload.php';

use Dotenv\Dotenv;

// Aponta onde o .env está localizado
$dotenv = Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"]);
$dotenv->load();

// Acessando as variáveis
$host=$_ENV['host'];
$username=$_ENV['username'];
$password=$_ENV['password'];
$dbname=$_ENV['dbname'];

// Conexão via PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$username,$password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão PDO: " . $e->getMessage());
}

// Supermode
if (preg_match_all("/register.php/",$_SERVER["SCRIPT_NAME"])&&preg_match_all("/supermode/",$_SERVER["QUERY_STRING"])){
	$supermode=true;
}else{
	$supermode=false;
}

// E-Mail API mock-up
function sendEMail($pdo,$em_sender,$em_dest,$em_subject,$em_msg){
	$date=new DateTimeImmutable("now",new DateTimeZone("UTC"));
	$em_time=$date->format("Y-m-d H:i:s");
	$em_status="0";

	$sql="INSERT INTO emails (`sent_at`,`sender`,`recipient`,`subject`,`message`,`status`) VALUES (:sent_at,:sender,:recipient,:subject,:message,:status)";
	$stmt=$pdo->prepare($sql);
	$stmt->execute([
		":sent_at" => $em_time,
		":sender" => $em_sender,
		":recipient" => $em_dest,
		":subject" => $em_subject,
		":message" => $em_msg,
		":status" => $em_status
	]);

	if ($stmt->affected_rows>0){
		$_SESSION["email_success"]="E-Mail message successfully sent! Check your inbox.";
	}else{
		$_SESSION["email_error"]="E-Mail not send! Internal error...";
    }

	return $stmt->affected_rows>0;/**/
}
?>