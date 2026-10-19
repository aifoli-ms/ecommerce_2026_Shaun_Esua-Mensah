<?php
require_once __DIR__ . "/../../controllers/ProductController.php";

$sidebarCategories = [];
$sidebarBrands = [];

try {
    $sidebarController = new ProductController();
    $sidebarCategories = $sidebarController->getAllCategories();
    $sidebarBrands = $sidebarController->getAllBrands();
} catch (Throwable $error) {
    // Keep the storefront available even if the filters cannot be loaded.
}
?>
<aside class="store-sidebar" aria-label="Product filters">
	<section>
		<h2>Categories</h2>
		<ul>
			<?php foreach ($sidebarCategories as $category) { ?>
				<li><a href="<?php echo htmlspecialchars(app_url("index.php") . "?cat=" . urlencode($category["cat_id"]), ENT_QUOTES, "UTF-8"); ?>"><?php echo htmlspecialchars($category["cat_name"], ENT_QUOTES, "UTF-8"); ?></a></li>
			<?php } ?>
		</ul>
	</section>
	<section>
		<h2>Brands</h2>
		<ul>
			<?php foreach ($sidebarBrands as $brand) { ?>
				<li><a href="<?php echo htmlspecialchars(app_url("index.php") . "?brand=" . urlencode($brand["brand_id"]), ENT_QUOTES, "UTF-8"); ?>"><?php echo htmlspecialchars($brand["brand_name"], ENT_QUOTES, "UTF-8"); ?></a></li>
			<?php } ?>
		</ul>
	</section>
</aside>
