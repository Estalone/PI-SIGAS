<?php /* coding: utf-8 */
include "./inc/connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
	<title>E-Mail Mockaccino</title>
    <style>
        td{
			white-space:nowrap;
        	overflow:hidden;
        	text-overflow:ellipsis;
		}
    </style>
</head>
<body>
	<h1>E-Mail Mockaccino</h1>
	<h2>The test E-Mail inbox</h2>
	<h3>Welcome!</h3>
	<hr>
    <table border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse;margin:auto">
	<tr><th>#</th><th>id</th><th>Timestamp</th><th>Sender</th><th>Destination</th><th>Subject</th><th>Message</th><th>Status</th></tr>
<?php
	$sql="SELECT * FROM emails ORDER BY sent_at DESC";
	$stmt=$pdo->prepare($sql);
	$stmt->execute();
	$em_cnt=0;

	$msg_rerp= array();
	$msg_rerp[0] ="/<[^>]>/";
	$msg_rerp[1] ="/&[^;];/";
	$msg_rerp[2] ="/[\"\'\`]/";
	$msg_rerr=array();
	$replacements[0] ="";
	$replacements[1] =".";
	$replacements[2] ="_";

	while($row=$stmt->fetch()){
		$em_cnt++;
		echo "<tr onclick=\"document.getElementById('read_msg_".$em_cnt."').showModal()\"><td>".$em_cnt."</td><td style=\"max-width:50px;\">".$row["id"]."</td><td>".$row["sent_at"]."</td><td style=\"max-width:50px;\" title=\"".$row["sender"]."\">".$row["sender"]."</td><td style=\"max-width:50px;\" title=\"".$row["recipient"]."\">".$row["recipient"]."</td><td style=\"max-width:50px;\" title=\"".$row["subject"]."\">".$row["subject"]."</td><td style=\"max-width:100px;\">".substr(preg_replace($msg_rerp,$msg_rerr,$row["message"]),0,20)."<dialog id=\"read_msg_".$em_cnt."\"><table border=\"1\" cellspacing=\"0\" cellpadding=\"4\"><tr><td>Origem:</td><td>".$row["sender"]."</td></tr><tr><td>Recipiente:</td><td>".$row["recipient"]."</td></tr><tr><td colspan=\"2\">".$row["message"]."</td></tr></table><button commandfor=\"read_msg_".$em_cnt."\" command=\"close\">Close</button></dialog></td><td>".$row["status"]."</td></tr>";
	}
	echo "</table>";

	if ($em_check==1)
		echo "<p>Total of ".$em_cnt." message.</p>";
	else
		echo "<p>Total of ".$em_cnt." messages.</p>";

	$conn->close();
?>
</body>
</html>
