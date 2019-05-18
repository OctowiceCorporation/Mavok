<?php


namespace App\DTO;


use DateTimeInterface;

class Blog
{
    private $title;
    private $description;
    private $image;
    private $created_at;
    private $updated_at;
    private $is_visible;


    public function __construct(string $title, string $description, string $image, DateTimeInterface $created_at, DateTimeInterface $updated_at, bool $is_visible)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->is_visible = $is_visible;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updated_at;
    }

    public function isIsVisible(): bool
    {
        return $this->is_visible;
    }
}