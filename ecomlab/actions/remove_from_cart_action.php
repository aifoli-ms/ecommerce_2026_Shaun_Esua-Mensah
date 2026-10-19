<?php

require_once __DIR__ . "/../core/core.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect(app_url("views/cart.php"));
}

require_once __DIR__ . "/../controllers/CartController.php";

$selectedProducts = $_POST["remove"] ?? [];

if (!is_array($selectedProducts) || empty($selectedProducts)) {
    $_SESSION["cart_error"] = "Please select at least one item to remove.";
    redirect(app_url("views/cart.php"));
}

$ip = get_ip();
$removedAny = false;
$invalidSelection = false;
$operationFailed = false;

try {
    $controller = new CartController();

    foreach ($selectedProducts as $submittedId) {
        $productId = is_scalar($submittedId)
            ? filter_var($submittedId, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])
            : false;

        if ($productId === false) {
            $invalidSelection = true;
            continue;
        }

        if ($controller->removeFromCart($productId, $ip)) {
            $removedAny = true;
        } else {
            $operationFailed = true;
        }
    }
} catch (Throwable $error) {
    $operationFailed = true;
}

if ($operationFailed) {
    $_SESSION["cart_error"] = "One or more items could not be removed.";
} elseif ($invalidSelection) {
    $_SESSION["cart_error"] = $removedAny
        ? "Selected valid items were removed; invalid selections were ignored."
        : "No valid cart items were selected.";
} elseif ($removedAny) {
    $_SESSION["cart_success"] = "Selected item(s) removed from your cart.";
}

redirect(app_url("views/cart.php"));
