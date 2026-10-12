<?php

require_once __DIR__ . "/../../core/core.php";

// Keep non-admin users away before sending any page output.
require_admin();

require_once __DIR__ . "/../../controllers/ProductController.php";

// Read each message once, then remove it from the session.
$success = $_SESSION["success"] ?? "";
$error = $_SESSION["error"] ?? "";
unset($_SESSION["success"], $_SESSION["error"]);

$brands = [];
$editBrand = false;
$isEditMode = false;

try {
    $controller = new ProductController();

    // Enter edit mode only when edit_id is a valid positive integer.
    if (isset($_GET["edit_id"])) {
        $editId = filter_var($_GET["edit_id"], FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1]
        ]);

        if ($editId === false) {
            $error = "Invalid brand selected.";
        } else {
            $editBrand = $controller->getBrandById($editId);

            if ($editBrand) {
                $isEditMode = true;
            } else {
                $error = "Brand not found.";
            }
        }
    }

    $brands = $controller->getAllBrands();
} catch (Throwable $exception) {
    if ($error === "") {
        $error = "Unable to load brands. Please try again.";
    }
}

$formAction = $isEditMode ? "actions/update_brand_action.php" : "actions/add_brand_action.php";
$brandName = $isEditMode ? $editBrand["brand_name"] : "";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Manage Brands</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<h1>Manage Brands</h1>

	<?php require __DIR__ . "/../layout/header.php"; ?>

	<?php if ($success !== "") { ?>
		<p class="success-message"><?php echo htmlspecialchars($success, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>

	<?php if ($error !== "") { ?>
		<p class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>

	<form action="<?php echo htmlspecialchars(app_url($formAction), ENT_QUOTES, "UTF-8"); ?>" method="POST">
		<?php if ($isEditMode) { ?>
			<input type="hidden" name="brand_id" value="<?php echo htmlspecialchars($editBrand["brand_id"], ENT_QUOTES, "UTF-8"); ?>">
		<?php } ?>

		<label for="brand_name">Brand Name</label>
		<input type="text" name="brand_name" id="brand_name" value="<?php echo htmlspecialchars($brandName, ENT_QUOTES, "UTF-8"); ?>" maxlength="100" required>

		<button type="submit"><?php echo $isEditMode ? "Update Brand" : "Add Brand"; ?></button>

		<?php if ($isEditMode) { ?>
			<a href="<?php echo htmlspecialchars(app_url("views/admin/brand.php"), ENT_QUOTES, "UTF-8"); ?>">Cancel</a>
		<?php } ?>
	</form>

	<h2>Brands</h2>

	<table>
		<thead>
			<tr>
				<th>Brand ID</th>
				<th>Brand Name</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($brands)) { ?>
				<tr>
					<td colspan="3">No brands found.</td>
				</tr>
			<?php } else { ?>
				<?php foreach ($brands as $brand) { ?>
					<tr>
						<td><?php echo htmlspecialchars($brand["brand_id"], ENT_QUOTES, "UTF-8"); ?></td>
						<td><?php echo htmlspecialchars($brand["brand_name"], ENT_QUOTES, "UTF-8"); ?></td>
						<td>
							<a href="<?php echo htmlspecialchars(app_url("views/admin/brand.php") . "?edit_id=" . urlencode($brand["brand_id"]), ENT_QUOTES, "UTF-8"); ?>">Edit</a>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</body>
</html>
