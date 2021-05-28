<?php
	session_start();
?>

<!DOCTYPE html>

<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" href="index.css">
		<title>Affinity VPN | Home</title>
    </head>

    <body>
    	<header>
			<?php include "nav.php" ?>
		</header>	
		<div class="bg">
					
		</div>	
		<div class="info">
			<div class="container">
				<div class="d-flex flex-row justify-content-around text-center">
					<div class="p-4">
						<img src="images/support.png">
						<p>24/7 Support</p>
					</div>
					<div class="p-4">
						<img src="images/no-log.png">
						<p>No logs</p>
					</div>
					<div class="p-4">
						<img src="images/computer.png">
						<br>
						<p>Connect up to 4 devices</p>
					</div>
				</div>
			</div>
		</div>
		<footer class="page-footer bg-dark">
			<?php include "footer.php"?>
		</footer>
   		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   		<script src="bootstrap/dist/js/bootstrap.bundle.js"></script>
    </body>
</html>
