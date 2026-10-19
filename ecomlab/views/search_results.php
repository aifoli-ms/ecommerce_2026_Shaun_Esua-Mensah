<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$submittedQuery = $_GET["user_query"] ?? "";
$query = is_string($submittedQuery) ? trim(strip_tags($submittedQuery)) : "";
$products = [];
$loadError = "";

if ($query !== "") {
    try {
        $controller = new ProductController();
        $products = $controller->searchProducts($query);
    } catch (Throwable $error) {
        $loadError = "Unable to search products. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Product Search</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<?php require __DIR__ . "/layout/header.php"; ?>
	<div class="store-layout">
		<?php require __DIR__ . "/layout/sidebar.php"; ?>
		<main class="store-main">
			<h1>Search Results<?php if ($query !== "") { ?> for “<?php echo htmlspecialchars($query, ENT_QUOTES, "UTF-8"); ?>”<?php } ?></h1>
			<?php if ($loadError !== "") { ?>
				<p class="error-message"><?php echo htmlspecialchars($loadError, ENT_QUOTES, "UTF-8"); ?></p>
			<?php } elseif ($query === "") { ?>
				<p>Please enter a search term.</p>
			<?php } elseif (empty($products)) { ?>
				<p>No products found.</p>
			<?php } else { ?>
				<div class="product-grid">
					<?php foreach ($products as $product) { ?>
						<article class="product-card">
							<img src="<?php echo htmlspecialchars(app_url("images/products/") . rawurlencode($product["product_image"]), ENT_QUOTES, "UTF-8"); ?>" alt="<?php echo htmlspecialchars($product["product_title"], ENT_QUOTES, "UTF-8"); ?>">
							<div class="product-card-body">
								<h2><?php echo htmlspecialchars($product["product_title"], ENT_QUOTES, "UTF-8"); ?></h2>
								<p class="product-price">GHS <?php echo htmlspecialchars(number_format((float) $product["product_price"], 2), ENT_QUOTES, "UTF-8"); ?></p>
								<div class="product-actions">
									<a href="<?php echo htmlspecialchars(app_url("views/single_product.php") . "?pro_id=" . urlencode($product["product_id"]), ENT_QUOTES, "UTF-8"); ?>">Details</a>
									<a href="<?php echo htmlspecialchars(app_url("actions/add_to_cart_action.php") . "?add_cart=" . urlencode($product["product_id"]), ENT_QUOTES, "UTF-8"); ?>">Add to Cart</a>
								</div>
							</div>
						</article>
					<?php } ?>
				</div>
			<?php } ?>
		</main>
	</div>
</body>
</html>
