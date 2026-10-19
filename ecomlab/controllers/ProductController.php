<?php

require_once __DIR__ . "/../classes/ProductClass.php";

// Connects product actions and views to the product model.
class ProductController
{
    private $product;

    public function __construct()
    {
        $this->product = new ProductClass();
    }

    // Ask the model to save a brand.
    public function addBrand($name)
    {
        return $this->product->addBrand($name);
    }

    // Ask the model for all brands.
    public function getAllBrands()
    {
        return $this->product->getAllBrands();
    }

    // Ask the model for one brand.
    public function getBrandById($id)
    {
        return $this->product->getBrandById($id);
    }

    // Ask the model to update a brand.
    public function updateBrand($id, $name)
    {
        return $this->product->updateBrand($id, $name);
    }

    // Ask the model to save a category.
    public function addCategory($name)
    {
        return $this->product->addCategory($name);
    }

    // Ask the model for all categories.
    public function getAllCategories()
    {
        return $this->product->getAllCategories();
    }

    // Ask the model for one category.
    public function getCategoryById($id)
    {
        return $this->product->getCategoryById($id);
    }

    // Ask the model to update a category.
    public function updateCategory($id, $name)
    {
        return $this->product->updateCategory($id, $name);
    }

    public function addProduct($cat, $brand, $title, $price, $desc, $imageFilename, $keywords)
    {
        return $this->product->addProduct($cat, $brand, $title, $price, $desc, $imageFilename, $keywords);
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $imageFilename, $keywords)
    {
        return $this->product->updateProduct($id, $cat, $brand, $title, $price, $desc, $imageFilename, $keywords);
    }

    public function getProductById($id)
    {
        return $this->product->getProductById($id);
    }

    public function getAllProducts()
    {
        return $this->product->getAllProducts();
    }

    public function getFeaturedProducts($limit = 6)
    {
        $limit = filter_var($limit, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

        return $this->product->getFeaturedProducts($limit === false ? 6 : $limit);
    }

    public function getProductsByCategory($catId)
    {
        return $this->product->getProductsByCategory($catId);
    }

    public function getProductsByBrand($brandId)
    {
        return $this->product->getProductsByBrand($brandId);
    }

    public function searchProducts($query)
    {
        return $this->product->searchProducts($query);
    }
}
