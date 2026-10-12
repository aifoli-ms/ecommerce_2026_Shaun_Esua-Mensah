<?php

// Start the session and load shared helper functions.
require_once __DIR__ . "/../core/core.php";

// Only administrators can add brands.
require_admin();

// This file should only process the add-brand form.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/admin/brand.php"));
}

require_once __DIR__ . "/../controllers/ProductController.php";

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
    $added = $controller->addBrand($name);
} catch (Throwable $error) {
    $added = false;
}

if ($added) {
    $_SESSION["success"] = "Brand added.";
} else {
    $_SESSION["error"] = "Unable to add the brand. Please try again.";
}

redirect(app_url("views/admin/brand.php"));
