<?php

require_once __DIR__ . "/../core/db_class.php";

// Handles all shopping-cart database queries and calculations.
class CartClass extends Database
{
    public function isInCart($product_id, $ip)
    {
        $sql = "SELECT * FROM cart WHERE p_id = ? AND ip_add = ? LIMIT 1";

        return $this->fetchOne($sql, [$product_id, $ip]) ? true : false;
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        $sql = "INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?)";

        return $this->execute($sql, [$product_id, $ip, $qty]);
    }

    public function getCartItems($ip)
    {
        $sql = "
            SELECT cart.*, products.product_title,
                   products.product_price, products.product_image
            FROM cart
            JOIN products ON cart.p_id = products.product_id
            WHERE cart.ip_add = ?
        ";

        return $this->fetchAll($sql, [$ip]);
    }

    public function getCartCount($ip)
    {
        $sql = "SELECT COUNT(*) AS item_count FROM cart WHERE ip_add = ?";
        $row = $this->fetchOne($sql, [$ip]);

        return (int) ($row["item_count"] ?? 0);
    }

    public function getCartTotal($ip)
    {
        $total = 0.0;

        foreach ($this->getCartItems($ip) as $item) {
            $total += (int) $item["qty"] * (float) $item["product_price"];
        }

        return $total;
    }

    public function removeFromCart($product_id, $ip)
    {
        $sql = "DELETE FROM cart WHERE p_id = ? AND ip_add = ?";

        return $this->execute($sql, [$product_id, $ip]);
    }

    public function updateQty($product_id, $new_qty, $ip)
    {
        if (!is_scalar($new_qty)) {
            return false;
        }

        $newQty = filter_var(
            $new_qty,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($newQty === false) {
            return false;
        }

        $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?";

        return $this->execute($sql, [$newQty, $product_id, $ip]);
    }
}
