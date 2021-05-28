<?php	
	session_start();
	include_once "config/db.php";
	$first_name = "";
	$last_name = "";
	$username = "";
	$password = "";
	$password_verify = "";
	$email = "";
	$phone_number = "";
	$billing_addr = "";
	$state = "";
	$zip = "";
	$country = "";
	if(isset($_POST["first_name"], $_POST["last_name"], $_POST["username"], $_POST["password"], $_POST["password_verify"], $_POST["email"], $_POST["phone_number"], $_POST["billing_addr"], $_POST["state"], $_POST["zip"], $_POST["country"])) {
		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$username = strtolower($_POST["username"]);
		$password = $_POST["password"];
		$password_verify = $_POST["password_verify"];
		$email = $_POST["email"];
		$phone_number = $_POST["phone_number"];
		$billing_addr = $_POST["billing_addr"];
		$state = $_POST["state"];
		$zip = $_POST["zip"];
		$country = $_POST["country"];
	}

	$passShortErr = "";
	$passMatchErr = "";
	$emailErr = "";
	$userErr = "";
	$domainErr = "";
	$domain = substr(strrchr($email, "@"), 1);
	

	if(isset($_POST["register"])) {
		//query database for username
		$database = new Connection();
		$conn = $database->openConnection();
		$statement = $conn->prepare("SELECT username FROM users WHERE username = ? AND email = ?");
		$statement->execute([$username, $email]);
		$results = $statement->fetchAll();

		if(count($results) > 0) {
			//username alredy exists
			$userErr = "Username already exists";
		} elseif(strlen($password) < 8) {
			//password does not meet reqirements
			$passShortErr = "Minimum of 8 characters";
		} elseif($password !== $password_verify) {
			//passwords do not match
			$passMatchErr = "Passwords do not match";
		} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				//verify email is formatted
				$emailErr = "Email isn't properly formatted";
		} elseif(!checkdnsrr($domain, "MX")) {
				//verify doamin exists
				$domainErr = "Doamin dosent exist, please check email again";
		} else {
			//create new user
			$statement = $conn->prepare("INSERT INTO users (name_last, name_first, username, password, email, phone_number, billing_address, state, zip, country_iso) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$statement->execute([$last_name, $first_name, $username, $hashed_password, $email, $phone_number, $billing_addr, $state, $zip, $country]);
			$hash = md5(rand(0,1000));
			$statement = $conn->prepare("INSERT INTO email_verification (email, hash) VALUES (?, ?)");
			$statement->execute([$email, $hash]);
			$link = "https://affinityvpn.com/verify.php?";
			$to = $email;
			$subject = 'Signup | Verification';
			$body = '
			Thank you for signing up!
			
			Your account has been created, but you must first veryify your email address
			in order to deliver the appropriate vpn file.

			Please click on this link to activate your account:
			
			https://affinityvpn.com/verify.php?email='.$email.'&hash='.$hash.'

			Thanks for using Affinity VPN,
			Joe Ackerson

			';

			$headers = 'From:no-reply@affinityvpn.com' . "\r\n";
			mail($to, $subject, $body, $headers);
			header("Location: login.php");
			
		}
	}
	
?>

<!DOCTYPE html>

<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" href="std.css">
		<title>Affinity VPN | Register</title>
    </head>

    <body>
		<header>
			<?php include "nav.php" ?>
		</header>
		<main>
			<div class="container-fluid">
			<br/>
				<form class="mx-auto col-lg-4" method="post">
					<div class="row form-group">
						<div class="col">
							<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo $first_name;?>" required>
						</div>
						<div class="col">
							<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $last_name;?>" required>
						</div>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php echo $username;?>" required>
						<span style="color:red"><?php echo $userErr;?></span>
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
						<span style="color:red"><?php echo $passShortErr;?></span>
					</div>	
					<div class="form-group">
						<input type="password" class="form-control" id="password_verify" name="password_verify" placeholder="Verify Password" required>
						<span style="color:red"><?php echo $passMatchErr;?></span>
					</div>
					<div class="form-group">
						<input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo $email;?>" required>
						<div class="help-block with-errors"></div>
						<span style="color:red"><?php echo $emailErr;?></span>
						<span style="color:red"><?php echo $domainErr;?></span>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" value="<?php echo $phone_number;?>" required>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="billing_addr" name="billing_addr" placeholder="Address" value="<?php echo $billing_addr;?>" required>
					</div>
					<div class="row">
						<div class="col">
							<input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" value="<?php echo $zip;?>" required>
						</div>
						<div class="col-3">
								<input type="text" class="form-control" id="state" name="state" placeholder="State" value="<?php echo $state;?>" required>
						</div>
						<div class="col-3">
							<input type="text" class="form-control" id="country" name="country" placeholder="Country" value="<?php echo $country;?>" required>
						</div>
					</div>
					<div class="form-group pt-3 text-center">
						<button type="submit" name="register" class="btn btn-dark btn-primary">Create Account</button>
					</div>
				</form>
			</div>
		</main>
		<footer class="page-footer bg-dark">
			<?php include "footer.php"?>
		</footer>
   		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   		<script src="bootstrap/dist/js/bootstrap.bundle.js"></script>
    </body>
</html>
