<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

$cartItems = [];
$cartTotal = 0.0;
$loadError = "";

try {
    $controller = new CartController();
    $ip = get_ip();
    $cartItems = $controller->getCartItems($ip);
    $cartTotal = $controller->getCartTotal($ip);
} catch (Throwable $error) {
    $loadError = "Unable to load your cart. Please try again.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Shopping Cart</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<?php require __DIR__ . "/layout/header.php"; ?>
	<main class="cart-page">
		<h1>Shopping Cart</h1>
		<?php if ($loadError !== "") { ?>
			<p class="error-message"><?php echo htmlspecialchars($loadError, ENT_QUOTES, "UTF-8"); ?></p>
		<?php } elseif (empty($cartItems)) { ?>
			<p>Your cart is empty.</p>
			<a class="action-link" href="<?php echo htmlspecialchars(app_url("index.php"), ENT_QUOTES, "UTF-8"); ?>">Continue Shopping</a>
		<?php } else { ?>
			<form id="remove-cart-form" method="POST" action="<?php echo htmlspecialchars(app_url("actions/remove_from_cart_action.php"), ENT_QUOTES, "UTF-8"); ?>"></form>
			<form id="update-cart-form" method="POST" action="<?php echo htmlspecialchars(app_url("actions/update_qty_action.php"), ENT_QUOTES, "UTF-8"); ?>"></form>
			<table class="cart-table">
					<thead>
						<tr>
							<th scope="col">Remove</th>
							<th scope="col">Product</th>
							<th scope="col">Image</th>
							<th scope="col">Quantity</th>
							<th scope="col">Unit Price</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($cartItems as $item) { ?>
							<tr>
								<td><input type="checkbox" name="remove[]" value="<?php echo htmlspecialchars($item["p_id"], ENT_QUOTES, "UTF-8"); ?>" form="remove-cart-form" aria-label="Remove <?php echo htmlspecialchars($item["product_title"], ENT_QUOTES, "UTF-8"); ?>"></td>
								<td><?php echo htmlspecialchars($item["product_title"], ENT_QUOTES, "UTF-8"); ?></td>
								<td><img class="cart-product-image" src="<?php echo htmlspecialchars(app_url("images/products/") . rawurlencode($item["product_image"]), ENT_QUOTES, "UTF-8"); ?>" alt="<?php echo htmlspecialchars($item["product_title"], ENT_QUOTES, "UTF-8"); ?>"></td>
								<td>
									<input class="cart-quantity" type="number" name="qty[]" value="<?php echo htmlspecialchars($item["qty"], ENT_QUOTES, "UTF-8"); ?>" min="1" step="1" form="update-cart-form">
									<input type="hidden" name="update[]" value="<?php echo htmlspecialchars($item["p_id"], ENT_QUOTES, "UTF-8"); ?>" form="update-cart-form">
								</td>
								<td>GHS <?php echo number_format((float) $item["product_price"], 2); ?></td>
							</tr>
						<?php } ?>
						<tr class="cart-subtotal">
							<th colspan="4" scope="row">Subtotal</th>
							<td>GHS <?php echo number_format($cartTotal, 2); ?></td>
						</tr>
					</tbody>
			</table>
			<div class="cart-actions">
				<button type="submit" form="remove-cart-form">Remove from Cart</button>
				<button type="submit" form="update-cart-form">Update Quantity</button>
				<a href="<?php echo htmlspecialchars(app_url("index.php"), ENT_QUOTES, "UTF-8"); ?>">Continue Shopping</a>
				<a class="cart-checkout" href="<?php echo htmlspecialchars(app_url("views/checkout.php"), ENT_QUOTES, "UTF-8"); ?>">Checkout</a>
			</div>
		<?php } ?>
	</main>
</body>
</html>
