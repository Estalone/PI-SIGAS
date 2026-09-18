<?php /* coding: utf-8 */
include "./header.php";
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
	$stmt=$conn->prepare("SELECT * FROM `t_email` ORDER BY `em_time` DESC");
	$stmt->execute();
	$result=$stmt->get_result();
    $em_cnt=0;

	while ($row=$result->fetch_assoc()){
		$em_cnt++;
		echo "<tr><td>".$em_cnt."</td><td>".$row["em_id"]."</td><td>".substr($row["em_time"],0,4)."-".substr($row["em_time"],4,2)."-".substr($row["em_time"],6,2)." ".substr($row["em_time"],8,2).":".substr($row["em_time"],10,2).":".substr($row["em_time"],12,2)."</td><td style=\"max-width:50px;\" title=\"".$row["em_sender"]."\">".$row["em_sender"]."</td><td style=\"max-width:50px;\" title=\"".$row["em_dest"]."\">".$row["em_dest"]."</td><td style=\"max-width:50px;\" title=\"".$row["em_subject"]."\">".$row["em_subject"]."</td><td style=\"width:100px;\">".$row["em_msg"]."</td><td>".$row["em_status"]."</td></tr>";
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