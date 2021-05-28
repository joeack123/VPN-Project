<?php
	$err = false;
	$errmsg = "";
	session_start();
	
	include_once "config/db.php";

	$username;
	if(isset($_POST["username"])) {
		$username = $_POST["username"];
	}

	$password;
	if(isset($_POST["password"])) {
		$password = $_POST["password"];
	}

	$captcha;
	if(isset($_POST["g-recaptcha-response"])) {
		$captcha = $_POST["g-recaptcha-response"];
	}

	$email;
	$hash;
	if(isset($_GET["email"], $_GET["hash"])) {
		$email = $_GET["email"];
		$hash = $_GET["hash"];
	}

	if(isset($_POST["verify"])) {
		//Query batabase for login information
		$database = new Connection();
		$conn = $database->openConnection();
		$statement = $conn->prepare("SELECT password FROM users WHERE username = ?");
		$statement->execute([$username]);
		$results = $statement->fetch();

		if($username == "" || $password == "") {
			$err = true;
			$errmsg = "Please enter a username and password.";
		} elseif(!$results) {
			$err = true;
			$errmsg = "Username does not exist.";
		} elseif (!password_verify($password, $results["password"])) {
			$err = true;
			$errmsg = "Incorrect Password.";
		} elseif(!$captcha) {
			$err = true;
			$errmsg = "Please complete the Captcha.";
		} else {
			$statement = $conn->prepare("UPDATE email_verification SET verified = 1 WHERE email = ? AND hash = ?");
			$statement->execute([$email, $hash]);
			$filename = "clients/" . strtolower($username) . ".txt";
			$content = $username . "-" . $password;
			file_put_contents($filename, $content);
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
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<link rel="stylesheet" href="std.css">
		<title>Affinity VPN | Verify</title>
	</head>
	<body>
		<header>
			<?php include "nav.php" ?>
		</header>
		<main class="bg">
			<div class="container sm-fluid">
				<br/><br/>
				<div class="row">
					<div class="col-sm-8 col-md-6 col-lg-4 mx-auto">
						<div class="card my-5">
							<div class="card-body">
								<?php if(isset($_POST["login"])) { ?>
									<?php if($err) { ?>
										<div class="alert alert-danger"><?php echo $errmsg;?></div>
									<?php } ?>
								<?php } ?>
								<form method="post">	
									<div class="form-group">
										<input type="tetx" class="form-control" id="username" name="username" placeholder="Enter username">
									</div>
									<div class="form-group">
										<input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
									</div>	
									<div class="form-group text-center">
										<div class="g-recaptcha" data-sitekey=""></div>
									</div>			
									<div class="form-group pt-3 text-center">
										<button type="submit" name="login" class="btn btn-dark">Login</button>
										<button type="submit" name="register" class="btn btn-dark">Register</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<footer class="page-footer bg-dark">
			<?php include "footer.php"?>
		</footer>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="bootstrap/dist/js/bootstrap.bundle.js"></script>
	</body>
</html>
