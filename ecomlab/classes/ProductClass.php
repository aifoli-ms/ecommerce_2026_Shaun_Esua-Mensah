<?php

require_once __DIR__ . "/../core/db_class.php";

// Handles all product-related database queries.
class ProductClass extends Database
{
    // Save a new brand.
    public function addBrand($name)
    {
        $sql = "INSERT INTO brands (brand_name) VALUES (?)";

        return $this->execute($sql, [$name]);
    }

    // Return all brands in alphabetical order.
    public function getAllBrands()
    {
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";

        return $this->fetchAll($sql);
    }

    // Find one brand using its ID.
    public function getBrandById($id)
    {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";

        return $this->fetchOne($sql, [$id]);
    }

    // Update an existing brand.
    public function updateBrand($id, $name)
    {
        $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";

        return $this->execute($sql, [$name, $id]);
    }

    // Save a new category.
    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (cat_name) VALUES (?)";

        return $this->execute($sql, [$name]);
    }

    // Return all categories in alphabetical order.
    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";

        return $this->fetchAll($sql);
    }

    // Find one category using its ID.
    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE cat_id = ?";

        return $this->fetchOne($sql, [$id]);
    }

    // Update an existing category.
    public function updateCategory($id, $name)
    {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";

        return $this->execute($sql, [$name, $id]);
    }

    // Save a new product.
    public function addProduct($cat, $brand, $title, $price, $desc, $imageFilename, $keywords)
    {
        $sql = "
            INSERT INTO products (
                product_cat,
                product_brand,
                product_title,
                product_price,
                product_desc,
                product_image,
                product_keywords
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $imageFilename, $keywords]);
    }

    // Update an existing product.
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $imageFilename, $keywords)
    {
        $sql = "
            UPDATE products
            SET product_cat = ?,
                product_brand = ?,
                product_title = ?,
                product_price = ?,
                product_desc = ?,
                product_image = ?,
                product_keywords = ?
            WHERE product_id = ?
        ";

        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $imageFilename, $keywords, $id]);
    }

    // Return one product together with its category and brand names.
    public function getProductById($id)
    {
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            WHERE p.product_id = ?
            LIMIT 1
        ";

        return $this->fetchOne($sql, [$id]);
    }

    // Return all products for the administration table.
    public function getAllProducts()
    {
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            ORDER BY p.product_id DESC
        ";

        return $this->fetchAll($sql);
    }

    // Return a limited selection of products for the storefront.
    public function getFeaturedProducts($limit = 6)
    {
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            ORDER BY RAND()
            LIMIT ?
        ";
        $statement = $this->getConnection()->prepare($sql);
        $statement->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Return products assigned to one category.
    public function getProductsByCategory($catId)
    {
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            WHERE p.product_cat = ?
            ORDER BY p.product_id DESC
        ";

        return $this->fetchAll($sql, [$catId]);
    }

    // Return products assigned to one brand.
    public function getProductsByBrand($brandId)
    {
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            WHERE p.product_brand = ?
            ORDER BY p.product_id DESC
        ";

        return $this->fetchAll($sql, [$brandId]);
    }

    // Find products whose title or keywords contain the search text.
    public function searchProducts($query)
    {
        $searchTerm = "%" . $query . "%";
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            WHERE p.product_title LIKE ? OR p.product_keywords LIKE ?
            ORDER BY p.product_id DESC
        ";

        return $this->fetchAll($sql, [$searchTerm, $searchTerm]);
    }
}
