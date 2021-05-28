<?php
	session_start();
	include_once "config/db.php";
	$time = $_SERVER['REQUEST_TIME'];
	$timeout = 1800;
	if(isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $timeout) {
		session_unset();
		session_destroy();
	}
	
	$_SESSION['LAST_ACTIVITY'] = $time;

	if(isset($_POST["logout"])) {
		session_unset();
		session_destroy();	
	}
	
	if(!isset($_SESSION["username"])) {
		header("Location: login.php");
	} else {
		$user_id = $_SESSION["user_id"];
		$username = $_SESSION["username"];
	}

	$email = "";
	$phone_number = "";
	$billing_addr = "";
	$state = "";
	$zip = "";
	$country = "";

	$database = new Connection();
	$conn = $database->openConnection();
	$statement = $conn->prepare("SELECT email, phone_number, billing_address, zip, state, country_iso, active FROM users WHERE username = ?");
	$statement->execute([$username]);
	$results = $statement->fetch();
	
	$email = $results["email"];
	$phone_number = $results["phone_number"];
	$billing_addr = $results["billing_address"];
	$zip = $results["zip"];
	$state = $results["state"];
	$country = $results["country_iso"];
	$active = $results["active"];

	$emailErr = "";
	$domainErr = "";
	$domain = substr(strrchr($email, "@"), 1);
	
	if(isset($_POST["save"])) {
		if(isset($_POST["email"], $_POST["phone_number"], $_POST["billing_addr"], $_POST["state"], $_POST["zip"], $_POST["country"])) {
			$email = $_POST["email"];
			$phone_number = $_POST["phone_number"];
			$billing_addr = $_POST["billing_addr"];
			$zip = $_POST["zip"];
			$state = $_POST["state"];
			$country = $_POST["country"];
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				//verify email is formatted
				$emailErr = "Email isn't properly formatted";
			} elseif(!checkdnsrr($domain, "MX")) {
				//verify doamin exists
				$domainErr = "Doamin dosent exist, please check email again";
			} else {
				$statement = $conn->prepare("UPDATE users SET email = ?, phone_number = ?, billing_address = ?, zip = ?, state = ?, country_iso = ? WHERE username = ?");
				$statement->execute([$email, $phone_number, $billing_addr, $zip, $state, $country, $username]);
			}
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
		<title>Affinity VPN | Account</title>
    </head>

    <body>
		<header>
			<?php include "nav.php" ?>
		</header>
		<main>
			<div class="container-fluid">
				<div class="row">
					<div class="col-2">
						<br/><br/><br/>
						<div class="nav flex-column nav-pills" id="tab" role="tablist">
							<a class="nav-link active" id="info-tab" data-toggle="pill" href="#info" role="tab">Information</a>
							<a class="nav-link" id="history-tab" data-toggle="pill" href="#history" role="tab">History</a>				
						</div>
					</div>
					<div class="col-10">
						<div class="tab-content" id="content">
							<div class="tab-pane fade show active" id="info" role="tabpanel">
								<br/><br/><br/>
								<form method="post">
									<div class="form-group col-lg-6 mx-auto">
										<label>Change Email</label>
										<input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>">
										<div class="help-block with-errors"></div>
										<span style="color:red"><?php echo $emailErr;?></span>
										<span style="color:red"><?php echo $domainErr;?></span>
										<label>Change Phone Number</label>
										<input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $phone_number;?>">
										<label>Change Billing Address</label>
										<input type="text" class="form-control" id="billing_addr" name="billing_addr" value="<?php echo $billing_addr;?>">
										<label>Change Zip Code</label>
										<input type="text" class="form-control" id="zip" name="zip" value="<?php echo $zip;?>">
										<label>Change State</label>
										<input type="text" class="form-control" id="state" name="state" value="<?php echo $state;?>">
										<label>Change Country</label>
										<input type="text" class="form-control" id="country" name="country" value="<?php echo $country;?>">
									</div>
									<br/>
									<div class="form-group pt-3 text-center">
										<button type="submit" name="save" class="btn btn-dark">Save Changes</button>
										<button type="submit" name="logout" class="btn btn-dark">Logout</button>
										<a class="btn btn-dark <?php if($active == 0) { echo disabled; }?>" href="files/<?php echo $username;?>.ovpn" download>Download client file</a>
									</div>
								</form>
								<br/><br/><br/>
							</div>
							<div class="tab-pane fade" id="history" role="tabpanel">
								<table class="table table-hover my-5">
									<thead class="thead-light">
										<tr>
											<th>Order Number</th>
											<th>Amount</th>
											<th>Plan Name</th>
											<th>Date Purchased</th>
										</tr>
									</thead>
								<?php
									$database = new Connection();
									$conn = $database->openConnection();
									$statement = $conn->prepare("SELECT order_number, amount, plan, start_date FROM history WHERE user_id = ?");
									$statement->execute([$user_id]);
									$results = $statement->fetchAll();
									if(count($results) > 0) {
										foreach($results as $row) {
											echo "<tr>";
												echo "<td>" . $row["order_number"] . "</td>";
												echo "<td>" . $row["amount"] . "</td>";
												echo "<td>" . $row["plan"]  . "</td>";
												echo "<td>" . $row["start_date"]  . "</td>";
											echo "</tr>";
										}
									}
								?>
								</table>
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