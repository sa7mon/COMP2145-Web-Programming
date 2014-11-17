<?php
session_start();

// Declare and fill in an array of products to display
$prodArray = Array(
				  0 => array("name" => "Pine Tree", 
				  			 "price" => "123.45"),
				  1 => array("name" => "Oak Tree",
				  			 "price" => "45.89"),
				  2 => array("name" => "Generic Bush",
				  			 "price" => "10.00")
				  );
if (isset($_GET['mode'])) {
	// The user submitted something. Find out what.
	if ($_GET['mode'] == "add") {
		// User added something to their cart
		$_SESSION['cartArray'][count($_SESSION['cartArray']) + 1] = array("name" => $prodArray[$_POST['prodtoadd']]['name'],
																		  "price" => $prodArray[$_POST['prodtoadd']]['price']);
	} 
	elseif ($_GET['mode'] == "remove") {
		// User removed something from their cart
		unset($_SESSION['cartArray'][$_POST['prodtoremove']]);
	} elseif ($_GET['mode'] == 'destroy') { // DEBUG
		// DEBUG destroys sessions variables
		session_unset();
	} 
	else {
		// User messed something up.
	}
}
$cartEmpty = true; // Set our cart as empty by default. We'll check for it later
$cartEmpty = (!count($_SESSION['cartArray']) > 0) ? true : false; // We're checking it right now

 //DEBUG STATEMENTS
/*var_dump($_POST);
echo "<br />";
var_dump($_SESSION['cartArray']); // Echo eveverything in the cart.
echo "Cart empty? ".$cartEmpty; */

?>
<html>
	<head>
		<title>Plants Inc.</title>
		<style type="text/css">
			body {
				background: grey;
			}
			div#content {
				padding:10px;
				background: white;
				width: 85%;
				background-image: url('graphic/tree.jpg');
    			background-repeat: no-repeat;
    			background-attachment: fixed;
    			background-position: center; 
				height:600px;
				margin: auto;
				-moz-border-radius: 15px;
				border-radius: 15px;
			}
			img {
    			opacity: 0.4;
    			filter: alpha(opacity=40); /* For IE8 and earlier */
			}
			fieldset {
				width: 30%;
			}
		</style>
	</head>
	<body>
		<div id="content">
		<div id="contentbkg">
		</div>
			<h2>Plants for sale</h2>
			<form action="index.php?mode=add" method="POST">
				<fieldset name="products" class="left">
					<legend>Products</legend>
					<table border="1">
						<tr>
							<th>Description</th>
							<th>Price</th>
						</tr>
						<?php
							foreach ($prodArray as $key => $prod) {
								// For every product we carry, list the details
								echo "<tr>
										<td>".$prod["name"]."</td>
										<td>$".$prod["price"]."</td>
									  </tr>";
							}
						?>
					</table>
				</fieldset>
				<fieldset class="left">
					<legend>Add To Cart</legend>
					<select name="prodtoadd">
					<?php
						foreach ($prodArray as $key => $prod) {
							echo "<option value=".$key.">".$prod['name']."</option>";
						}
					?>
					</select>
					<input type="submit" value="Add" />
				</fieldset>
			</form>
			<?php
				if ($cartEmpty == false) { //PROBLEM: Couldn't get this to change state. SOLUTION: Double-equal sign.
					// There's stuff in our cart
					?>
					<fieldset class="right">
						<legend>Your Cart</legend>
						<table border="1">
							<tr>
								<th>Name</th>
								<th>Price</th>
								<?php 
									$cartTotal = 0;
									//Back in PHP mode. Time to loop through our cart and output everything
									foreach ($_SESSION['cartArray'] as $num => $product) {
										// For every product in the cart, do the following
										echo "<tr><td>".$product['name']."</td><td>$".$product['price']."</td></tr>";
										$cartTotal += $product['price'];
									}
									echo "<tr><td><strong>Total</strong></td><td><strong>$".$cartTotal."</strong></td></tr>";
								?>
							</tr>
						</table>
					</fieldset>
					<fieldset class="right">
						<legend>Remove From Cart</legend>
						<form name="remove" action="index.php?mode=remove" method="POST">
							<select name="prodtoremove">
								<?php
									foreach ($_SESSION['cartArray'] as $num => $product) {
										// For every product in the cart, do the following
										echo "<option value=".$num.">".$num.". ".$product['name']." - ".$product['price']."</option>";
									}
								?>
							</select>
							<input type="submit" value="Remove" />
						</form>
					</fieldset>
					<?php
				} else {
					// We've got no cart!
					?>
					<fieldset class="right">
						<legend>Cart</legend>
						<p>Empty!</p>
					</fieldset>
					<?php
				}
			?>
		</div>
	</body>
</html>