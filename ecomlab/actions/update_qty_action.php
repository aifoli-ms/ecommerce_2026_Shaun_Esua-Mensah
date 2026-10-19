<?php

require_once __DIR__ . "/../core/core.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/cart.php"));
}

require_once __DIR__ . "/../controllers/CartController.php";

$submittedQuantities = $_POST["qty"] ?? [];
$submittedProducts = $_POST["update"] ?? [];

if (!is_array($submittedQuantities) || !is_array($submittedProducts) || empty($submittedProducts)) {
    $_SESSION["cart_error"] = "No valid quantities were submitted.";
    redirect(app_url("views/cart.php"));
}

$quantities = array_values($submittedQuantities);
$productIds = array_values($submittedProducts);
$ip = get_ip();
$updatedAny = false;
$invalidEntry = count($quantities) !== count($productIds);
$operationFailed = false;
$pairCount = min(count($quantities), count($productIds));

try {
    $controller = new CartController();

    for ($index = 0; $index < $pairCount; $index++) {
        $productId = is_scalar($productIds[$index])
            ? filter_var($productIds[$index], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])
            : false;
        $quantity = is_scalar($quantities[$index])
            ? filter_var($quantities[$index], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])
            : false;

        if ($productId === false || $quantity === false) {
            $invalidEntry = true;
            continue;
        }

        if ($controller->updateQty($productId, $quantity, $ip)) {
            $updatedAny = true;
        } else {
            $operationFailed = true;
        }
    }
} catch (Throwable $error) {
    $operationFailed = true;
}

if ($operationFailed) {
    $_SESSION["cart_error"] = "One or more quantities could not be updated.";
} elseif ($invalidEntry) {
    $_SESSION["cart_error"] = $updatedAny
        ? "Valid quantities were updated; invalid quantities were rejected."
        : "Quantity must be a whole number of at least 1.";
} elseif ($updatedAny) {
    $_SESSION["cart_success"] = "Cart quantities updated.";
}

redirect(app_url("views/cart.php"));
