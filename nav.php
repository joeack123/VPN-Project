<?php

	include_once "config/db.php";

	$username = "";
	if(isset($_SESSION["username"])) {
		$username = $_SESSION["username"];
	}

	$user_id = "";
	if(isset($_SESSION["user_id"])) {
		$user_id = $_SESSION["user_id"];
	}
	
	$database = new Connection();
	$conn = $database->openConnection();
	$statement = $conn->prepare("SELECT name_first FROM users WHERE username = ?");
	$statement->execute([$username]);
	$results = $statement->fetch();	

?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
	<img src="images/afflogo2.png" width="96" height="70" alt="Page Logo">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNavDropdown">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='index.php'){ echo " active"; }?>" href="index.php">Home</a>
			</li>
			<li class="nav-item">
				<a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='pricing.php'){ echo " active"; }?>" href="pricing.php">Pricing</a>
			</li>
			<li class="nav-item">
				<a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='help.php'){ echo " active"; }?>" href="help.php">Help</a>
			</li>
			<li class="nav-item">
				<a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='account.php'){ echo " active"; }?>" href="account.php"><?php if(isset($_SESSION["username"])) {echo "Welcome, " . $results["name_first"];} else {echo "Account";}  ?></a>
			</li>
		</ul>
	</div>
</nav>
