<?php
include_once "./restricted/multipass.php";

// Create Connection
$conn=new mysqli($host,$username,$password,$dbname);
mysqli_set_charset($conn,"utf8mb4");

// Check if database connection failed, then die
if(!$conn){
	die("SQL database server connection failed: ".mysqli_connect_error());
}

if (preg_match_all("/register.php/",$_SERVER["SCRIPT_NAME"])&&preg_match_all("/supermode/",$_SERVER["QUERY_STRING"])){
	$supermode=true;
}else{
	$supermode=false;
}

function sendEMail($sql_conn,$em_sender,$em_dest,$em_subject,$em_msg){
	$date=new DateTimeImmutable("now",new DateTimeZone("UTC"));
	$em_time=$date->format("YmdHis");
	$em_status="0";

	$sql="INSERT INTO `t_email` (`em_time`,`em_sender`,`em_dest`,`em_subject`,`em_msg`,`em_status`) VALUES (?,?,?,?,?,?)";

	$stmt=$sql_conn->prepare($sql);
	$stmt->bind_param("sssssi",$em_time,$em_sender,$em_dest,$em_subject,$em_msg,$em_status);
	$stmt->execute();

	if ($stmt->affected_rows>0){
		$_SESSION["success_msg"]="E-Mail message successfully sent! Check your inbox.";
	}else{
		$_SESSION["error_msg"]="E-Mail not send! Internal error...";
    }

	return $stmt->affected_rows>0;/**/
}
?>