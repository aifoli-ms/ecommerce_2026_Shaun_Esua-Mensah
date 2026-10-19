<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

// Return to the referring page only when it belongs to this application host.
$returnUrl = app_url("index.php");
$referrer = $_SERVER["HTTP_REFERER"] ?? "";
$referrerHost = parse_url($referrer, PHP_URL_HOST);
$currentHost = preg_replace('/:\\d+$/', '', $_SERVER["HTTP_HOST"] ?? "");

if ($referrer !== "" && $referrerHost !== null && strcasecmp($referrerHost, $currentHost) === 0) {
    $returnUrl = $referrer;
}

$productId = filter_var(
    $_GET["add_cart"] ?? null,
    FILTER_VALIDATE_INT,
    ["options" => ["min_range" => 1]]
);

if ($productId === false || $productId === null) {
    $_SESSION["cart_error"] = "Please choose a valid product.";
    redirect($returnUrl);
}

$ip = get_ip();
$qty = 1;
$customerId = is_logged_in() ? $_SESSION["customer_id"] : null;

try {
    $controller = new CartController();

    if ($controller->isInCart($productId, $ip)) {
        $_SESSION["cart_error"] = "This item is already in your cart. Go to cart to update quantity.";
        redirect($returnUrl);
    }

    if ($controller->addToCart($productId, $qty, $ip, $customerId)) {
        $_SESSION["cart_success"] = "Item added to your cart.";
    } else {
        $_SESSION["cart_error"] = "Unable to add this item to your cart.";
    }
} catch (Throwable $error) {
    $_SESSION["cart_error"] = "Unable to add this item to your cart.";
}

redirect($returnUrl);
