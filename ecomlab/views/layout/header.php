<?php
require_once __DIR__ . "/../../core/core.php";
?>
<!-- Show different links based on the login session. -->
<nav>
	<a href="<?php echo htmlspecialchars(app_url("index.php"), ENT_QUOTES, "UTF-8"); ?>">Home</a> |
	<?php if (is_logged_in()) { ?>
		Welcome <?php echo htmlspecialchars($_SESSION["customer_name"] ?? "Customer", ENT_QUOTES, "UTF-8"); ?> |
		<a href="<?php echo htmlspecialchars(app_url("view/account/my_account.php"), ENT_QUOTES, "UTF-8"); ?>">My Account</a> |
		<a href="<?php echo htmlspecialchars(app_url("actions/logout_action.php"), ENT_QUOTES, "UTF-8"); ?>">Logout</a>
	<?php } else { ?>
		<a href="<?php echo htmlspecialchars(app_url("view/register.php"), ENT_QUOTES, "UTF-8"); ?>">Register</a> |
		<a href="<?php echo htmlspecialchars(app_url("views/login.php"), ENT_QUOTES, "UTF-8"); ?>">Login</a>
	<?php } ?>
</nav>
