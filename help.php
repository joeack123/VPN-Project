<?php
	session_start();
?>

<!DOCTYPE html>

<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" href="help.css">
		<title>Affinity VPN | Help</title>
    </head>

    <body>
		<header>
			<?php include "nav.php" ?>
		</header>
		<main>
			<section class="windows">
				<div class="row">
					<div class="col-sm-8">
						<h1>Windows Installation Guide</h1>
						<p>Step 1: Download the latest openvpn installer <a href="https://build.openvpn.net/downloads/releases/latest/openvpn-install-latest-stable.exe">here.</a></p>
						<p>Step 2: Run the installer by double-clicking on the file and follow the prompts.</p>
						<p>Step 3: Download the .ovpn file that can be found in your account page after you have purchased a subscription.</p>
						<p>Step 4: Rigth-click on the OpenVPN gui by which is located in the caret menu (^) on the bottom right-hand corner of your screen.</p>
						<p>Step 5: Import the configuration file that you downloaded by selecting import file and selecting the file you want to import.</p>
						<p>Step 6: Click the the newly imported OpenVPN configuration that you just imported and hit the connect button and enter your username and password and now you're connected!</p>
					</div>
				</div>
			</section>
			<section class="android">
				<div class="row">
					<div class="col-sm-8">
						<h1>Android Installation Guide</h1>
						<p>Step 1: Open the play store and search for the OpenVPN app and install it.</p>
						<p>Step 2: Download the .ovpn file that can be found in your account page after you have purchased a subscription.</p>
						<p>Step 3: Navigate to the section called "OVPN Profile" and import your .ovpn file you downloaded from the last step.</p>
						<p>Step 4: Then hit connect and you should be connected to the vpn.</p>
					</div>
				</div>
			</section>
			<section class="ios">
				<div class="row">
					<div class="col-sm-8">
						<h1>IOS Installation Guide</h1>
						<p>Step 1: Open the IOS App Store and search for the openvpn app and download it.</p>
						<p>Step 2: Next download the .ovpn file from your account page as you will need it in the next step.</p>
						<p>Step 3: Click on the download file to open it in the openvpn app.</p>
						<p>Step 4: Click on add and then the configuration file should be imported into the app</p>
						<p>Step 5: Connect to the VPN!</p>
					</div>
				</div>
			</section>
		</main>
		<footer class="page-footer bg-dark">
			<?php include "footer.php"?>
		</footer>
   		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   		<script src="bootstrap/dist/js/bootstrap.bundle.js"></script>
    </body>
</html>
