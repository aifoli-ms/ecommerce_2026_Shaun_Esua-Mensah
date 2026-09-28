<?php
require_once __DIR__ . "/core/core.php";

// Read the message once, then remove it from the session.
$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ecom LAB</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<h1>This is ecom lab started</h1>
	<!-- Load the shared navigation. -->
	<?php require __DIR__ . "/views/layout/header.php"; ?>

	<?php if ($error !== "") { ?>
		<p class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>
</body>
</html>
