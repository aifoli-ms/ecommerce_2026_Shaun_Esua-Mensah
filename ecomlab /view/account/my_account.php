<?php
require_once __DIR__ . "/../../core/core.php";
require_login();

require_once __DIR__ . "/../../controllers/CustomerController.php";

$controller = new CustomerController();
$customer = $controller->getCustomerById($_SESSION["customer_id"]);

if (!$customer) {
	// The account may have been removed after this session was created.
	$_SESSION = [];
	$_SESSION["error"] = "Your account could not be found. Please log in again.";
	redirect(app_url("views/login.php"));
}

// Keep the navigation greeting in sync with the database.
$_SESSION["customer_name"] = $customer["customer_name"];
$_SESSION["customer_email"] = $customer["customer_email"];
$_SESSION["user_role"] = $customer["user_role"];

$success = $_SESSION["success"] ?? "";
unset($_SESSION["success"]);

$nameParts = preg_split('/\s+/', trim($customer["customer_name"]));
$initials = strtoupper(substr($nameParts[0] ?? "C", 0, 1));
if (count($nameParts) > 1) {
	$initials .= strtoupper(substr(end($nameParts), 0, 1));
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>My Account</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css") . "?v=" . filemtime(__DIR__ . "/../../css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<?php require __DIR__ . "/../../views/layout/header.php"; ?>

	<main class="account-page">
		<?php if ($success !== "") { ?>
			<p class="success-message account-alert"><?php echo htmlspecialchars($success, ENT_QUOTES, "UTF-8"); ?></p>
		<?php } ?>

		<header class="account-hero">
			<div class="account-avatar" aria-hidden="true"><?php echo htmlspecialchars($initials, ENT_QUOTES, "UTF-8"); ?></div>
			<div>
				<p class="account-eyebrow">Customer account</p>
				<h1><?php echo htmlspecialchars($customer["customer_name"], ENT_QUOTES, "UTF-8"); ?></h1>
				<p class="account-subtitle">View your personal information and account details.</p>
			</div>
		</header>

		<section class="account-card">
			<h2>Profile details</h2>
			<p class="account-card-intro">The information linked to your Shoppn account.</p>
			<dl class="account-details">
				<div>
					<dt>Name</dt>
					<dd><?php echo htmlspecialchars($customer["customer_name"], ENT_QUOTES, "UTF-8"); ?></dd>
				</div>
				<div>
					<dt>Email</dt>
					<dd><?php echo htmlspecialchars($customer["customer_email"], ENT_QUOTES, "UTF-8"); ?></dd>
				</div>
				<div>
					<dt>Contact</dt>
					<dd><?php echo htmlspecialchars($customer["customer_contact"], ENT_QUOTES, "UTF-8"); ?></dd>
				</div>
				<div>
					<dt>Location</dt>
					<dd><?php echo htmlspecialchars($customer["customer_city"] . ", " . $customer["customer_country"], ENT_QUOTES, "UTF-8"); ?></dd>
				</div>
			</dl>
		</section>
	</main>
</body>
</html>
