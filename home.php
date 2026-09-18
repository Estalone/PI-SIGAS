<?php /* coding: utf-8 */
include "./header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.9">
	<link rel="stylesheet" href="./css/main.css">
	<title>Yaguara - Login</title>
</head>
<body>
	<h1>Yaguara</h1>
	<h2>Login</h2>
	<h3>Welcome, <?php echo $_SESSION['user_name']; ?>!</h3>
	<hr>
	<h4>Your Account Details:</h4>
	<dl>
	<dt>Id</dt>
	<dd><?= $_SESSION['user_id'] ?></dd>
	<dt>User Name</dt>
	<dd><?= $_SESSION['user_name'] ?></dd>
	<dt>User Type</dt>
	<dd><?= $_SESSION['user_type'] ?></dd>
	<dt>E-Mail</dt>
	<dd><?= $_SESSION['user_email'] ?></dd>
	<dt>User Status</dt>
	<dd><?= $_SESSION['user_status'] ?></dd>
	</dl>

	<?php
	$conn->close();
	?>

	<form action="./logout.php">
		<input class="Submit" type="submit" title="Log out" value="Log out"/>
	</form>
	<script type="text/javascript" src="./localisation.js"></script>
</body></html>