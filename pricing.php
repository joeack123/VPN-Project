<?php
	require_once('vendor/autoload.php');
	include_once "config/db.php";
	
	session_start();

	\Stripe\Stripe::setApiKey('');

	$plan;
	$session;
	if(isset($_POST["submit"])) {
		if(isset($_SESSION["username"])) {
			$plan = $_POST["plans"];
			$session = \Stripe\Checkout\Session::create([
				'payment_method_types' => ['card'],
				'subscription_data' => [
					'items' => [[
						'plan' => $plan,
					]],
				],
				'success_url' => 'https://affinityvpn.com/account.php',
				'cancel_url' => 'https://affinityvpn.com/index.php',
			]);
	
			//wite to history database here
			$total;
			$name;
			if($plan = 'plan_F6ant0ADe49TIi') {
				$name = '1 yr plan';
				$total = '$60.00 USD';	
			} elseif($plan = 'plan_F6b9uCCe9hJSrt') {
				$name = '6 mnth plan';
				$total = '$42.00 USD';
			} elseif($plan = 'plan_F6bBM7JvnV2Quc') {
				$name = '3 mnth plan';
				$total = '$27.00 USD';
			} elseif($plan = 'plan_F6bC9FfLguhdR9') {
				$name = '1 mnth plan';
				$total = '$11.00 USD';
			}
			$user_id = $_SESSION["user_id"];
			$ordernum = str_pad(rand(0, pow(10, 10)-1), 10, '0', STR_PAD_LEFT);
			$start = date("Y-m-d");
			$database = new Connection();
			$conn = $database->openConnection();
			$statement = $conn->prepare("INSERT INTO history (user_id, order_number, sub_id, amount, plan, start_date) VALUES (?, ?, ?, ?, ?, ?)");
			$statement->execute([$user_id, $ordernum, $plan, $total, $name, $start]);
			$statement = $conn->prepare("UPDATE users SET active = 1 WHERE username = ? AND user_id = ?");
			$statement->execute([$_SESSION["username"], $user_id]);
		} else {
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
		<script src="https://js.stripe.com/v3/"></script>
		<title>Affinity VPN | Pricing</title>
    </head>

	<script>
		var stripe = Stripe('stripe info');

		stripe.redirectToCheckout({
			sessionId: '<?php echo $session->id;?>'
		}).then(function(result) {
			if(result.error) {
				var displayError = document.getElementById('error-message');
				displayError.textContent = result.error.message;
			}
		});
	</script>

    <body>
		<header>
			<?php include "nav.php" ?>
		</header>
		<main class="bg">
			<div class="container sm-fluid text-center">
			   	<br/><br/><br/><br/><br/><br/><br/>
			   	<form method="post">
					<div class="btn-group d-inline" data-toggle="buttons">
						<div class="card-deck">
				    		<div class="card">
						 	 	<div class="card-body">
					   				<h5 class="card-title">1-year plan</h5>
				    				<br/>
						 			<h3>$5.00</h3>
				    				<br/>
						  			<span>Billed as $60.00 every year</span>
						    	</div>
						    	<div class="card-body">
									<label class="btn btn-primary">
										<input type="radio" name="plans" value="plan_F6ant0ADe49TIi" checked> Purchase
									</label>
						    	</div>
						   	</div>
						   	<div class="card">
						 		<div class="card-body">
				    				<h5 class="card-title">6-month plan</h5>
						   			<br/>
						    		<h3>$7.00</h3>
						    		<br/>
						    		<span>Billed as $42.00 every 6 months</span>
						   		</div>
								<div class="card-body">
						 			<label class="btn btn-primary">
						 				<input type="radio" name="plans" value="plan_F6b9uCCe9hJSrt"> Purchase
						 			</label>
						   		</div>
						    </div>
						    <div class="card">
						    	<div class="card-body">
						   			<h5 class="card-title">3-month plan</h5>
						   			<br/>
						    		<h3>$9.00</h3>
						    		<br/>
						   			<span>Billed as $27.00 every 3 months</span>
						   		</div>
						    	<div class="card-body">
						   			<label class="btn btn-primary">
						  					<input type="radio" name="plans" value="plan_F6bBM7JvnV2Quc"> Purchase
						   			</label>
						    	</div>
						   	</div>
						   	<div class="card">
						  		<div class="card-body">
					 				<h5 class="card-title">1-month plan</h5>
						   			<br/>
						    		<h3>$11</h3>
						   			<br/>
						    		<span>Billed as $11.00 monthly</span>
						    	</div>
						    	<div class="card-body">
									<label class="btn btn-primary">
										<input type="radio" name="plans" value="plan_F6bC9FfLguhdR9"> Purchase
									</label>
						    	</div>
				    		</div>
				    	</div>
					</div>
					<br/><br/>
					<div>
						<button type="submit" class="btn btn-primary" name="submit">Checkout</button>
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
