<?php
require_once __DIR__ . "/../../core/core.php";
require_once __DIR__ . "/../../controllers/CartController.php";

$cartCount = 0;
$cartTotal = 0.0;
$cartMessage = $_SESSION["cart_success"] ?? "";
$cartError = $_SESSION["cart_error"] ?? "";
unset($_SESSION["cart_success"], $_SESSION["cart_error"]);

try {
	$cartController = new CartController();
	$cartIp = get_ip();
	$cartCount = $cartController->getCartCount($cartIp);
	$cartTotal = $cartController->getCartTotal($cartIp);
} catch (Throwable $error) {
	// Keep the rest of the page available if the cart cannot be loaded.
}
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
	<div class="cart-summary">
		<span>Shopping Cart — Total Items: <?php echo $cartCount; ?> | Total Price: GHS <?php echo number_format($cartTotal, 2); ?></span>
		<a href="<?php echo htmlspecialchars(app_url("views/cart.php"), ENT_QUOTES, "UTF-8"); ?>">Go to Cart</a>
	</div>
</nav>
<?php if ($cartMessage !== "") { ?>
	<p class="success-message cart-message"><?php echo htmlspecialchars($cartMessage, ENT_QUOTES, "UTF-8"); ?></p>
<?php } ?>
<?php if ($cartError !== "") { ?>
	<p class="error-message cart-message"><?php echo htmlspecialchars($cartError, ENT_QUOTES, "UTF-8"); ?></p>
<?php } ?>
