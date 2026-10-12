<?php
require_once __DIR__ . "/../../core/core.php";
require_admin();
require_once __DIR__ . "/../../controllers/ProductController.php";

$success = $_SESSION["success"] ?? "";
$error = $_SESSION["error"] ?? "";
unset($_SESSION["success"], $_SESSION["error"]);

$categories = [];
$brands = [];
$products = [];
$editProduct = false;
$isEditMode = false;

try {
    $controller = new ProductController();

    if (isset($_GET["edit_id"])) {
        $editId = filter_var($_GET["edit_id"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if ($editId === false) {
            $error = "Invalid product selected.";
        } else {
            $editProduct = $controller->getProductById($editId);
            if ($editProduct) {
                $isEditMode = true;
            } else {
                $error = "Product not found.";
            }
        }
    }

    $categories = $controller->getAllCategories();
    $brands = $controller->getAllBrands();
    $products = $controller->getAllProducts();
} catch (Throwable $exception) {
    if ($error === "") {
        $error = "Unable to load products. Please try again.";
    }
}

$formAction = $isEditMode ? "actions/update_product_action.php" : "actions/add_product_action.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Manage Products</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<h1>Manage Products</h1>
	<?php require __DIR__ . "/../layout/header.php"; ?>

	<?php if ($success !== "") { ?>
		<p class="success-message"><?php echo htmlspecialchars($success, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>
	<?php if ($error !== "") { ?>
		<p class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>

	<form action="<?php echo htmlspecialchars(app_url($formAction), ENT_QUOTES, "UTF-8"); ?>" method="POST" enctype="multipart/form-data">
		<?php if ($isEditMode) { ?>
			<input type="hidden" name="product_id" value="<?php echo htmlspecialchars($editProduct["product_id"], ENT_QUOTES, "UTF-8"); ?>">
			<input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editProduct["product_image"], ENT_QUOTES, "UTF-8"); ?>">
		<?php } ?>

		<label for="product_title">Product Title</label>
		<input type="text" name="product_title" id="product_title" value="<?php echo htmlspecialchars($isEditMode ? $editProduct["product_title"] : "", ENT_QUOTES, "UTF-8"); ?>" required>

		<label for="product_price">Price</label>
		<input type="number" name="product_price" id="product_price" min="0" step="0.01" value="<?php echo htmlspecialchars($isEditMode ? $editProduct["product_price"] : "", ENT_QUOTES, "UTF-8"); ?>" required>

		<label for="product_desc">Description</label>
		<textarea name="product_desc" id="product_desc" rows="5"><?php echo htmlspecialchars($isEditMode ? $editProduct["product_desc"] : "", ENT_QUOTES, "UTF-8"); ?></textarea>

		<label for="product_keywords">Keywords</label>
		<input type="text" name="product_keywords" id="product_keywords" value="<?php echo htmlspecialchars($isEditMode ? $editProduct["product_keywords"] : "", ENT_QUOTES, "UTF-8"); ?>">

		<label for="product_cat">Category</label>
		<select name="product_cat" id="product_cat" required>
			<option value="">Select a category</option>
			<?php foreach ($categories as $category) { ?>
				<option value="<?php echo htmlspecialchars($category["cat_id"], ENT_QUOTES, "UTF-8"); ?>"<?php echo $isEditMode && (string) $editProduct["product_cat"] === (string) $category["cat_id"] ? " selected" : ""; ?>><?php echo htmlspecialchars($category["cat_name"], ENT_QUOTES, "UTF-8"); ?></option>
			<?php } ?>
		</select>

		<label for="product_brand">Brand</label>
		<select name="product_brand" id="product_brand" required>
			<option value="">Select a brand</option>
			<?php foreach ($brands as $brand) { ?>
				<option value="<?php echo htmlspecialchars($brand["brand_id"], ENT_QUOTES, "UTF-8"); ?>"<?php echo $isEditMode && (string) $editProduct["product_brand"] === (string) $brand["brand_id"] ? " selected" : ""; ?>><?php echo htmlspecialchars($brand["brand_name"], ENT_QUOTES, "UTF-8"); ?></option>
			<?php } ?>
		</select>

		<label for="product_image">Product Image<?php echo $isEditMode ? " (leave blank to keep current image)" : ""; ?></label>
		<input type="file" name="product_image" id="product_image" accept="image/jpeg,image/png,image/gif,image/webp"<?php echo $isEditMode ? "" : " required"; ?>>
		<?php if ($isEditMode && $editProduct["product_image"] !== "") { ?>
			<img class="current-product-image" src="<?php echo htmlspecialchars(app_url("images/products/") . rawurlencode($editProduct["product_image"]), ENT_QUOTES, "UTF-8"); ?>" alt="Current product image">
		<?php } ?>

		<button type="submit"><?php echo $isEditMode ? "Update Product" : "Add Product"; ?></button>
		<?php if ($isEditMode) { ?>
			<a href="<?php echo htmlspecialchars(app_url("views/admin/product.php"), ENT_QUOTES, "UTF-8"); ?>">Cancel</a>
		<?php } ?>
	</form>

	<h2>Products</h2>
	<div class="table-scroll">
		<table class="product-table">
			<thead><tr><th>Title</th><th>Price</th><th>Category</th><th>Brand</th><th>Image</th><th>Keywords</th><th>Action</th></tr></thead>
			<tbody>
				<?php if (empty($products)) { ?>
					<tr><td colspan="7">No products found.</td></tr>
				<?php } else { ?>
					<?php foreach ($products as $product) { ?>
						<tr>
							<td><?php echo htmlspecialchars($product["product_title"], ENT_QUOTES, "UTF-8"); ?></td>
							<td>GHS <?php echo htmlspecialchars(number_format((float) $product["product_price"], 2), ENT_QUOTES, "UTF-8"); ?></td>
							<td><?php echo htmlspecialchars($product["cat_name"] ?? "", ENT_QUOTES, "UTF-8"); ?></td>
							<td><?php echo htmlspecialchars($product["brand_name"] ?? "", ENT_QUOTES, "UTF-8"); ?></td>
							<td><img class="product-thumbnail" src="<?php echo htmlspecialchars(app_url("images/products/") . rawurlencode($product["product_image"]), ENT_QUOTES, "UTF-8"); ?>" alt=""></td>
							<td><?php echo htmlspecialchars($product["product_keywords"], ENT_QUOTES, "UTF-8"); ?></td>
							<td><a href="<?php echo htmlspecialchars(app_url("views/admin/product.php") . "?edit_id=" . urlencode($product["product_id"]), ENT_QUOTES, "UTF-8"); ?>">Edit</a></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</body>
</html>
