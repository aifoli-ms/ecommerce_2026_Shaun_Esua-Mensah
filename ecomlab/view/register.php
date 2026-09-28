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
	<title>Register Customer</title>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>
	<h1>Customer Registration</h1>

	<nav>
		<a href="../index.php">Home</a>
	</nav>

	<?php if ($error !== "") { ?>
		<p class="error-message"><?php echo htmlspecialchars($error); ?></p>
	<?php } ?>

	<!-- Send the form to the registration action. -->
	<form id="registrationForm" action="../actions/register_action.php" method="POST" enctype="multipart/form-data" novalidate>
		<label for="customer_name">Full Name</label>
		<input type="text" name="customer_name" id="customer_name">
		<span class="field-error" id="customer_name_error"></span>

		<label for="customer_email">Email</label>
		<input type="email" name="customer_email" id="customer_email">
		<span class="field-error" id="customer_email_error"></span>

		<label for="customer_pass">Password</label>
		<input type="password" name="customer_pass" id="customer_pass">
		<span class="field-error" id="customer_pass_error"></span>

		<label for="customer_country">Country</label>
		<input type="text" name="customer_country" id="customer_country">
		<span class="field-error" id="customer_country_error"></span>

		<label for="customer_city">City</label>
		<input type="text" name="customer_city" id="customer_city">
		<span class="field-error" id="customer_city_error"></span>

		<label for="customer_contact">Contact Number</label>
		<input type="text" name="customer_contact" id="customer_contact">
		<span class="field-error" id="customer_contact_error"></span>

		<label for="customer_address">Address</label>
		<textarea name="customer_address" id="customer_address" rows="3"></textarea>
		<span class="field-error" id="customer_address_error"></span>

		<label for="customer_image">Profile Image (optional)</label>
		<input type="file" name="customer_image" id="customer_image" accept="image/*">

		<button type="submit" id="registerButton">Register</button>
	</form>

	<script src="../js/validate.js"></script>
</body>
</html>
