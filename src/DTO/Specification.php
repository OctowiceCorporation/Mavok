<?php


namespace App\DTO;


class Specification
{
    private $name;
    private $unit;
    private $value;
    private $product_id;

    public function __construct(string $name, string $unit = null, string $value = null, int $product_id = null)
    {
        $this->name = $name;
        $this->unit = $unit;
        $this->value = $value;
        $this->product_id = $product_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }
}