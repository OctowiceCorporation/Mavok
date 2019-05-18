<?php


namespace App\DTO;


use DateTimeInterface;

class Product
{
    private $category_id;
    private $name;
    private $description;
    private $wholesale_price;
    private $retail_price;
    private $created_at;
    private $updated_at;
    private $is_available;
    private $is_visible;
    private $special_offer;
    private $manufacturer;
    private $producing_country;
    private $minimum_wholesale;
    private $sale;
    private $currency_id;
    private $product_unit;

    public function __construct(int $category_id, string $name, float $retail_price, DateTimeInterface $created_at, DateTimeInterface $updated_at, bool $is_available, bool $is_visible, bool $special_offer, string $description = null, float $wholesale_price = null, string $manufacturer = null, string $producing_country = null, int $minimum_wholesale = null, float $sale = null, int $currency_id = null, string $product_unit = null)
    {
        $this->category_id = $category_id;
        $this->name = $name;
        $this->description = $description;
        $this->wholesale_price = $wholesale_price;
        $this->retail_price = $retail_price;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->is_available = $is_available;
        $this->is_visible = $is_visible;
        $this->special_offer = $special_offer;
        $this->manufacturer = $manufacturer;
        $this->producing_country = $producing_country;
        $this->minimum_wholesale = $minimum_wholesale;
        $this->sale = $sale;
        $this->currency_id = $currency_id;
        $this->product_unit = $product_unit;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getWholesalePrice(): ?float
    {
        return $this->wholesale_price;
    }

    public function getRetailPrice(): float
    {
        return $this->retail_price;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updated_at;
    }

    public function isIsAvailable(): bool
    {
        return $this->is_available;
    }

    public function isIsVisible(): bool
    {
        return $this->is_visible;
    }

    public function isSpecialOffer(): bool
    {
        return $this->special_offer;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function getProducingCountry(): ?string
    {
        return $this->producing_country;
    }

    public function getMinimumWholesale(): ?int
    {
        return $this->minimum_wholesale;
    }

    public function getSale(): ?float
    {
        return $this->sale;
    }

    public function getCurrencyId(): ?int
    {
        return $this->currency_id;
    }

    public function getProductUnit(): ?string
    {
        return $this->product_unit;
    }
}