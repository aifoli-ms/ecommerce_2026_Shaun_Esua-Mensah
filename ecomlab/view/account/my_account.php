<?php
require_once __DIR__ . "/../../core/core.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>My Account</title>
	<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
	<h1>My Account</h1>

	<nav>
		<a href="../../index.php">Home</a>
	</nav>

	<!-- Show whether a customer session is available. -->
	<?php if (isset($_SESSION["customer_id"])) { ?>
		<p>Registration successful. Your customer ID is <?php echo htmlspecialchars($_SESSION["customer_id"]); ?>.</p>
	<?php } else { ?>
		<p>No customer session was found.</p>
	<?php } ?>
</body>
</html>
