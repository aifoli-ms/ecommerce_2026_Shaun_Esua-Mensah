<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
$products = [];
$pageTitle = "Featured Products";

try {
    $controller = new ProductController();

    if (isset($_GET["cat"])) {
        $filterId = filter_var($_GET["cat"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if ($filterId !== false) {
            $products = $controller->getProductsByCategory($filterId);
            $pageTitle = "Products by Category";
        }
    } elseif (isset($_GET["brand"])) {
        $filterId = filter_var($_GET["brand"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if ($filterId !== false) {
            $products = $controller->getProductsByBrand($filterId);
            $pageTitle = "Products by Brand";
        }
    } else {
        $products = $controller->getFeaturedProducts();
    }
} catch (Throwable $exception) {
    $error = $error !== "" ? $error : "Unable to load products. Please try again.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ecom LAB</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<?php require __DIR__ . "/layout/header.php"; ?>

	<div class="store-layout">
		<?php require __DIR__ . "/layout/sidebar.php"; ?>
		<main class="store-main">
			<h1><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, "UTF-8"); ?></h1>
			<?php if ($error !== "") { ?>
				<p class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
			<?php } ?>

			<?php if (empty($products)) { ?>
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
