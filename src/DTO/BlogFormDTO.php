<?php


namespace App\DTO;


class BlogFormDTO
{
    private $title;
    private $description;
    private $image;
    private $is_visible;

    public function __construct($title = null, $description = null, $is_visible = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->is_visible = $is_visible;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getIsVisible()
    {
        return $this->is_visible;
    }

    public function setIsVisible($is_visible): void
    {
        $this->is_visible = $is_visible;
    }
}