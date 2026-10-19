<?php

// Start the session and load shared helper functions.
require_once __DIR__ . "/../core/core.php";

// Only administrators can update categories.
require_admin();

// This file should only process the update-category form.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/admin/category.php"));
}

require_once __DIR__ . "/../controllers/ProductController.php";

// Validate the submitted category ID as a positive integer.
$submittedId = $_POST["category_id"] ?? "";
$id = filter_var($submittedId, FILTER_VALIDATE_INT, [
    "options" => ["min_range" => 1]
]);

if ($id === false) {
    $_SESSION["error"] = "Unable to update category.";
    redirect(app_url("views/admin/category.php"));
}

// Remove extra spaces and HTML tags from the submitted category name.
$submittedName = $_POST["category_name"] ?? "";
$name = is_string($submittedName) ? trim(strip_tags($submittedName)) : "";

if ($name === "") {
    $_SESSION["error"] = "Category name is required.";
    redirect(app_url("views/admin/category.php"));
}

if (strlen($name) > 100) {
    $_SESSION["error"] = "Category name must be 100 characters or less.";
    redirect(app_url("views/admin/category.php"));
}

try {
    $controller = new ProductController();
    $category = $controller->getCategoryById($id);
    $updated = $category ? $controller->updateCategory($id, $name) : false;
} catch (Throwable $error) {
    $updated = false;
}

if ($updated) {
    $_SESSION["success"] = "Category updated.";
} else {
    $_SESSION["error"] = "Unable to update category.";
}

redirect(app_url("views/admin/category.php"));
