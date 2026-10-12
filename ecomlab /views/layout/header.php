<?php
require_once __DIR__ . "/../../core/core.php";
?>
<!-- Show different links based on the login session. -->
<nav class="site-nav">
	<div class="nav-inner">
		<a class="nav-brand" href="<?php echo htmlspecialchars(app_url("index.php"), ENT_QUOTES, "UTF-8"); ?>">Shoppn</a>

		<div class="nav-primary">
			<a href="<?php echo htmlspecialchars(app_url("index.php"), ENT_QUOTES, "UTF-8"); ?>">Home</a>
			<?php if (is_admin()) { ?>
				<a href="<?php echo htmlspecialchars(app_url("views/admin/brand.php"), ENT_QUOTES, "UTF-8"); ?>">Brands</a>
				<a href="<?php echo htmlspecialchars(app_url("views/admin/category.php"), ENT_QUOTES, "UTF-8"); ?>">Categories</a>
				<a href="<?php echo htmlspecialchars(app_url("views/admin/product.php"), ENT_QUOTES, "UTF-8"); ?>">Products</a>
			<?php } ?>
		</div>

		<form class="nav-search" action="<?php echo htmlspecialchars(app_url("views/search_results.php"), ENT_QUOTES, "UTF-8"); ?>" method="GET">
			<label class="visually-hidden" for="site-search">Search products</label>
			<input type="search" name="user_query" id="site-search" placeholder="Search products" required>
			<button type="submit">Search</button>
		</form>

		<div class="nav-account">
			<?php if (is_logged_in()) { ?>
				<span>Welcome, <?php echo htmlspecialchars($_SESSION["customer_name"] ?? "Customer", ENT_QUOTES, "UTF-8"); ?></span>
				<a href="<?php echo htmlspecialchars(app_url("view/account/my_account.php"), ENT_QUOTES, "UTF-8"); ?>">My Account</a>
				<a class="nav-button" href="<?php echo htmlspecialchars(app_url("actions/logout_action.php"), ENT_QUOTES, "UTF-8"); ?>">Logout</a>
			<?php } else { ?>
				<a href="<?php echo htmlspecialchars(app_url("view/register.php"), ENT_QUOTES, "UTF-8"); ?>">Register</a>
				<a class="nav-button" href="<?php echo htmlspecialchars(app_url("views/login.php"), ENT_QUOTES, "UTF-8"); ?>">Login</a>
			<?php } ?>
		</div>
	</div>
</nav>
