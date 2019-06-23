<?php


namespace App\DTO;


class ProductFormDTO
{
    private $category;
    private $name;
    private $description;
    private $wholesale_price;
    private $retail_price;
    private $created_at;
    private $updated_at;
    private $is_available;
    private $is_visible;
    private $special_offer;
    private $minimum_wholesale;
    private $sale;
    private $product_value;
    private $product_unit;
    private $slug;
    private $images;
    private $currency_name;
    private $brand;
    private $amount;
    private $specification;

    public function __construct($category = null, $name = null, $wholesale_price = null, $retail_price = null, $is_available = null, $is_visible = null, $special_offer = null, $minimum_wholesale = null, $product_unit = null, $currency_name = null, $brand = null, $specification = null, $sale = null)
    {
        $this->category = $category;
        $this->name = $name;
        $this->wholesale_price = $wholesale_price;
        $this->retail_price = $retail_price;
        $this->is_available = $is_available;
        $this->is_visible = $is_visible;
        $this->special_offer = $special_offer;
        $this->minimum_wholesale = $minimum_wholesale;
        $this->product_unit = $product_unit;
        $this->currency_name = $currency_name;
        $this->brand = $brand;
        $this->specification = $specification;
        $this->sale = $sale;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category): void
    {
        $this->category = $category;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getWholesalePrice()
    {
        return $this->wholesale_price;
    }

    public function setWholesalePrice($wholesale_price): void
    {
        $this->wholesale_price = $wholesale_price;
    }

    public function getRetailPrice()
    {
        return $this->retail_price;
    }

    public function setRetailPrice($retail_price): void
    {
        $this->retail_price = $retail_price;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getIsAvailable()
    {
        return $this->is_available;
    }

    public function setIsAvailable($is_available): void
    {
        $this->is_available = $is_available;
    }

    public function getIsVisible()
    {
        return $this->is_visible;
    }

    public function setIsVisible($is_visible): void
    {
        $this->is_visible = $is_visible;
    }

    public function getSpecialOffer()
    {
        return $this->special_offer;
    }

    public function setSpecialOffer($special_offer): void
    {
        $this->special_offer = $special_offer;
    }

    public function getMinimumWholesale()
    {
        return $this->minimum_wholesale;
    }

    public function setMinimumWholesale($minimum_wholesale): void
    {
        $this->minimum_wholesale = $minimum_wholesale;
    }

    public function getSale()
    {
        return $this->sale;
    }

    public function setSale($sale): void
    {
        $this->sale = $sale;
    }

    public function getProductValue()
    {
        return $this->product_value;
    }

    public function setProductValue($product_value): void
    {
        $this->product_value = $product_value;
    }

    public function getProductUnit()
    {
        return $this->product_unit;
    }

    public function setProductUnit($product_unit): void
    {
        $this->product_unit = $product_unit;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images): void
    {
        $this->images = $images;
    }

    public function getCurrencyName()
    {
        return $this->currency_name;
    }

    public function setCurrencyName($currency_name): void
    {
        $this->currency_name = $currency_name;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand): void
    {
        $this->brand = $brand;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function setSpecification($specification): void
    {
        $this->specification = $specification;
    }

}