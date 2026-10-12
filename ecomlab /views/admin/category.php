<?php

require_once __DIR__ . "/../../core/core.php";

// Keep non-admin users away before sending any page output.
require_admin();

require_once __DIR__ . "/../../controllers/ProductController.php";

// Read each message once, then remove it from the session.
$success = $_SESSION["success"] ?? "";
$error = $_SESSION["error"] ?? "";
unset($_SESSION["success"], $_SESSION["error"]);

$categories = [];
$editCategory = false;
$isEditMode = false;

try {
    $controller = new ProductController();

    // Enter edit mode only when edit_id is a valid positive integer.
    if (isset($_GET["edit_id"])) {
        $editId = filter_var($_GET["edit_id"], FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1]
        ]);

        if ($editId === false) {
            $error = "Invalid category selected.";
        } else {
            $editCategory = $controller->getCategoryById($editId);

            if ($editCategory) {
                $isEditMode = true;
            } else {
                $error = "Category not found.";
            }
        }
    }

    $categories = $controller->getAllCategories();
} catch (Throwable $exception) {
    if ($error === "") {
        $error = "Unable to load categories. Please try again.";
    }
}

$formAction = $isEditMode ? "actions/update_category_action.php" : "actions/add_category_action.php";
$categoryName = $isEditMode ? $editCategory["cat_name"] : "";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Manage Categories</title>
	<link rel="stylesheet" href="<?php echo htmlspecialchars(app_url("css/style.css"), ENT_QUOTES, "UTF-8"); ?>">
</head>
<body>
	<h1>Manage Categories</h1>

	<?php require __DIR__ . "/../layout/header.php"; ?>

	<?php if ($success !== "") { ?>
		<p class="success-message"><?php echo htmlspecialchars($success, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>

	<?php if ($error !== "") { ?>
		<p class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?></p>
	<?php } ?>

	<form action="<?php echo htmlspecialchars(app_url($formAction), ENT_QUOTES, "UTF-8"); ?>" method="POST">
		<?php if ($isEditMode) { ?>
			<input type="hidden" name="category_id" value="<?php echo htmlspecialchars($editCategory["cat_id"], ENT_QUOTES, "UTF-8"); ?>">
		<?php } ?>

		<label for="category_name">Category Name</label>
		<input type="text" name="category_name" id="category_name" value="<?php echo htmlspecialchars($categoryName, ENT_QUOTES, "UTF-8"); ?>" maxlength="100" required>

		<button type="submit"><?php echo $isEditMode ? "Update Category" : "Add Category"; ?></button>

		<?php if ($isEditMode) { ?>
			<a href="<?php echo htmlspecialchars(app_url("views/admin/category.php"), ENT_QUOTES, "UTF-8"); ?>">Cancel</a>
		<?php } ?>
	</form>

	<h2>Categories</h2>

	<table>
		<thead>
			<tr>
				<th>Category ID</th>
				<th>Category Name</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($categories)) { ?>
				<tr>
					<td colspan="3">No categories found.</td>
				</tr>
			<?php } else { ?>
				<?php foreach ($categories as $category) { ?>
					<tr>
						<td><?php echo htmlspecialchars($category["cat_id"], ENT_QUOTES, "UTF-8"); ?></td>
						<td><?php echo htmlspecialchars($category["cat_name"], ENT_QUOTES, "UTF-8"); ?></td>
						<td>
							<a href="<?php echo htmlspecialchars(app_url("views/admin/category.php") . "?edit_id=" . urlencode($category["cat_id"]), ENT_QUOTES, "UTF-8"); ?>">Edit</a>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</body>
</html>
