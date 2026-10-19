<?php

// Start the session and load shared helper functions.
require_once __DIR__ . "/../core/core.php";

// Only administrators can update brands.
require_admin();

// This file should only process the update-brand form.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/admin/brand.php"));
}

require_once __DIR__ . "/../controllers/ProductController.php";

// Validate the submitted brand ID as a positive integer.
$submittedId = $_POST["brand_id"] ?? "";
$id = filter_var($submittedId, FILTER_VALIDATE_INT, [
    "options" => ["min_range" => 1]
]);

if ($id === false) {
    $_SESSION["error"] = "Unable to update brand.";
    redirect(app_url("views/admin/brand.php"));
}

// Remove extra spaces and HTML tags from the submitted brand name.
$submittedName = $_POST["brand_name"] ?? "";
$name = is_string($submittedName) ? trim(strip_tags($submittedName)) : "";

if ($name === "") {
    $_SESSION["error"] = "Brand name is required.";
    redirect(app_url("views/admin/brand.php"));
}

if (strlen($name) > 100) {
    $_SESSION["error"] = "Brand name must be 100 characters or less.";
    redirect(app_url("views/admin/brand.php"));
}

try {
    $controller = new ProductController();
    $brand = $controller->getBrandById($id);
    $updated = $brand ? $controller->updateBrand($id, $name) : false;
} catch (Throwable $error) {
    $updated = false;
}

if ($updated) {
    $_SESSION["success"] = "Brand updated.";
} else {
    $_SESSION["error"] = "Unable to update brand.";
}

redirect(app_url("views/admin/brand.php"));
