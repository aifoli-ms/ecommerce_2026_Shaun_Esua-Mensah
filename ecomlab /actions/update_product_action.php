<?php

require_once __DIR__ . "/../core/core.php";
require_admin();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/admin/product.php"));
}

require_once __DIR__ . "/../controllers/ProductController.php";

$fail = function ($message) {
    $_SESSION["error"] = $message;
    redirect(app_url("views/admin/product.php"));
};

$id = filter_var($_POST["product_id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$cat = filter_var($_POST["product_cat"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$brand = filter_var($_POST["product_brand"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$title = is_string($_POST["product_title"] ?? null) ? trim(strip_tags($_POST["product_title"])) : "";
$description = is_string($_POST["product_desc"] ?? null) ? trim(strip_tags($_POST["product_desc"])) : "";
$keywords = is_string($_POST["product_keywords"] ?? null) ? trim(strip_tags($_POST["product_keywords"])) : "";
$priceInput = $_POST["product_price"] ?? null;
$price = is_scalar($priceInput) && is_numeric($priceInput) ? (float) $priceInput : false;
$existingImage = is_string($_POST["existing_image"] ?? null) ? basename($_POST["existing_image"]) : "";

if ($id === false || $cat === false || $brand === false || $title === "" || $price === false || $price < 0) {
    $fail("Please enter valid product details.");
}

try {
    $controller = new ProductController();
    $currentProduct = $controller->getProductById($id);
} catch (Throwable $error) {
    $currentProduct = false;
}

if (!$currentProduct) {
    $fail("Unable to update product.");
}

if ($existingImage !== basename($currentProduct["product_image"] ?? "")) {
    $fail("Unable to update product.");
}

$imageFilename = $existingImage;
$newImagePath = "";
$upload = $_FILES["product_image"] ?? null;
$uploadError = is_array($upload) ? ($upload["error"] ?? UPLOAD_ERR_NO_FILE) : UPLOAD_ERR_NO_FILE;

if ($uploadError !== UPLOAD_ERR_NO_FILE) {
    if ($uploadError !== UPLOAD_ERR_OK || ($upload["size"] ?? 0) <= 0 || $upload["size"] > 2 * 1024 * 1024 || !is_uploaded_file($upload["tmp_name"])) {
        $fail("Product images must be valid files no larger than 2MB.");
    }

    $allowedMimeTypes = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/gif" => "gif",
        "image/webp" => "webp"
    ];
    $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($upload["tmp_name"]);
    if (!isset($allowedMimeTypes[$mimeType])) {
        $fail("Only JPEG, PNG, GIF, and WebP images are allowed.");
    }

    $originalName = pathinfo(basename($upload["name"]), PATHINFO_FILENAME);
    $safeBaseName = preg_replace('/[^A-Za-z0-9_-]/', "_", $originalName);
    $safeBaseName = $safeBaseName !== "" ? $safeBaseName : "image";
    $imageFilename = uniqid("product_", true) . "_" . $safeBaseName . "." . $allowedMimeTypes[$mimeType];
    $uploadDirectory = __DIR__ . "/../images/products";

    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
        $fail("Unable to update product.");
    }

    $newImagePath = $uploadDirectory . DIRECTORY_SEPARATOR . $imageFilename;
    if (!move_uploaded_file($upload["tmp_name"], $newImagePath)) {
        $fail("Unable to save the product image.");
    }
}

try {
    $updated = $controller->updateProduct($id, $cat, $brand, $title, $price, $description, $imageFilename, $keywords);
} catch (Throwable $error) {
    $updated = false;
}

if (!$updated) {
    if ($newImagePath !== "" && is_file($newImagePath)) {
        unlink($newImagePath);
    }
    $_SESSION["error"] = "Unable to update product.";
} else {
    $_SESSION["success"] = "Product updated.";
}

redirect(app_url("views/admin/product.php"));
