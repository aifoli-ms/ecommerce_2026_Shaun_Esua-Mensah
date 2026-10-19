<?php
require_once __DIR__ . "/../core/core.php";

// Read the error once, then remove it from the session.
$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Customer Login</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<h1>Customer Login</h1>

	<!-- Load the shared navigation. -->
	<?php require __DIR__ . "/layout/header.php"; ?>

	<?php if ($error !== "") { ?>
		<p class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>

	<!-- Send the login details to the login action. -->
	<form action="<?php echo htmlspecialchars(app_url("actions/login_action.php"), ENT_QUOTES, "UTF-8"); ?>" method="POST">
		<label for="customer_email">Email</label>
		<input type="email" name="customer_email" id="customer_email" required autocomplete="email">

		<label for="customer_pass">Password</label>
		<input type="password" name="customer_pass" id="customer_pass" required autocomplete="current-password">

		<button type="submit">Login</button>
	</form>

	<p>Don't have an account? <a href="<?php echo htmlspecialchars(app_url("view/register.php"), ENT_QUOTES, "UTF-8"); ?>">Register</a></p>
</body>
</html>
