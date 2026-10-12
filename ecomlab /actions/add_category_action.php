<?php

// Start the session and load shared helper functions.
require_once __DIR__ . "/../core/core.php";

// Only administrators can add categories.
require_admin();

// This file should only process the add-category form.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/admin/category.php"));
}

require_once __DIR__ . "/../controllers/ProductController.php";

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
    $added = $controller->addCategory($name);
} catch (Throwable $error) {
    $added = false;
}

if ($added) {
    $_SESSION["success"] = "Category added.";
} else {
    $_SESSION["error"] = "Unable to add category.";
}

redirect(app_url("views/admin/category.php"));
