<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$productId = filter_var($_GET["pro_id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
if ($productId === false || $productId === null) {
    redirect(app_url("index.php"));
}

try {
    $controller = new ProductController();
    $product = $controller->getProductById($productId);
} catch (Throwable $error) {
    $product = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo htmlspecialchars($product ? $product["product_title"] : "Product", ENT_QUOTES, "UTF-8"); ?></title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<?php require __DIR__ . "/layout/header.php"; ?>
	<div class="store-layout">
		<?php require __DIR__ . "/layout/sidebar.php"; ?>
		<main class="store-main">
			<?php if (!$product) { ?>
				<h1>Product not found</h1>
				<p>The product you requested is not available.</p>
			<?php } else { ?>
				<article class="product-detail">
					<img src="<?php echo htmlspecialchars(app_url("images/products/") . rawurlencode($product["product_image"]), ENT_QUOTES, "UTF-8"); ?>" alt="<?php echo htmlspecialchars($product["product_title"], ENT_QUOTES, "UTF-8"); ?>">
					<div>
						<h1><?php echo htmlspecialchars($product["product_title"], ENT_QUOTES, "UTF-8"); ?></h1>
						<p><strong>Category:</strong> <?php echo htmlspecialchars($product["cat_name"] ?? "Uncategorised", ENT_QUOTES, "UTF-8"); ?></p>
						<p><strong>Brand:</strong> <?php echo htmlspecialchars($product["brand_name"] ?? "Unbranded", ENT_QUOTES, "UTF-8"); ?></p>
						<p class="product-price">GHS <?php echo htmlspecialchars(number_format((float) $product["product_price"], 2), ENT_QUOTES, "UTF-8"); ?></p>
						<p><?php echo nl2br(htmlspecialchars($product["product_desc"], ENT_QUOTES, "UTF-8")); ?></p>
						<p><strong>Keywords:</strong> <?php echo htmlspecialchars($product["product_keywords"], ENT_QUOTES, "UTF-8"); ?></p>
						<a class="action-link" href="<?php echo htmlspecialchars(app_url("index.php") . "?add_cart=" . urlencode($product["product_id"]), ENT_QUOTES, "UTF-8"); ?>">Add to Cart</a>
					</div>
				</article>
			<?php } ?>
		</main>
	</div>
</body>
</html>
