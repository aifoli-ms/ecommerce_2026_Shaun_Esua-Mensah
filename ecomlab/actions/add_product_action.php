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

$cat = filter_var($_POST["product_cat"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$brand = filter_var($_POST["product_brand"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$title = is_string($_POST["product_title"] ?? null) ? trim(strip_tags($_POST["product_title"])) : "";
$description = is_string($_POST["product_desc"] ?? null) ? trim(strip_tags($_POST["product_desc"])) : "";
$keywords = is_string($_POST["product_keywords"] ?? null) ? trim(strip_tags($_POST["product_keywords"])) : "";
$priceInput = $_POST["product_price"] ?? null;
$price = is_scalar($priceInput) && is_numeric($priceInput) ? (float) $priceInput : false;

if ($cat === false || $brand === false || $title === "" || $price === false || $price < 0) {
    $fail("Please enter valid product details.");
}

$upload = $_FILES["product_image"] ?? null;
if (!is_array($upload) || ($upload["error"] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $fail("Please choose a product image.");
}

if (($upload["size"] ?? 0) <= 0 || $upload["size"] > 2 * 1024 * 1024 || !is_uploaded_file($upload["tmp_name"])) {
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
    $fail("Unable to add product.");
}

$destination = $uploadDirectory . DIRECTORY_SEPARATOR . $imageFilename;
if (!move_uploaded_file($upload["tmp_name"], $destination)) {
    $fail("Unable to save the product image.");
}

try {
    $controller = new ProductController();
    $added = $controller->addProduct($cat, $brand, $title, $price, $description, $imageFilename, $keywords);
} catch (Throwable $error) {
    $added = false;
}

if (!$added) {
    if (is_file($destination)) {
        unlink($destination);
    }
    $_SESSION["error"] = "Unable to add product.";
} else {
    $_SESSION["success"] = "Product added.";
}

redirect(app_url("views/admin/product.php"));
