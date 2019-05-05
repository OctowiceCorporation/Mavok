<?php


namespace App\DTO;


class Image
{
    private $product_id;
    private $image_path;

    public function __construct(string $image_path, int $product_id = null)
    {
        $this->product_id = $product_id;
        $this->image_path = $image_path;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function getImagePath(): string
    {
        return $this->image_path;
    }


}