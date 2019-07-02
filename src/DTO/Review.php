<?php


namespace App\DTO;


use DateTime;

class Review
{
    private $name;
    private $review;
    private $pros;
    private $cons;
    private $date;
    private $is_visible;

    public function __construct(string $name = null, string $review = null, string $pros = null, string $cons = null, DateTime $date = null, bool $is_visible = null)
    {
        $this->name = $name;
        $this->review = $review;
        $this->pros = $pros;
        $this->cons = $cons;
        $this->date = $date;
        $this->is_visible = $is_visible;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function getPros(): ?string
    {
        return $this->pros;
    }

    public function getCons(): ?string
    {
        return $this->cons;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function isIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setReview(string $review): void
    {
        $this->review = $review;
    }

    public function setPros(string $pros): void
    {
        $this->pros = $pros;
    }

    public function setCons(string $cons): void
    {
        $this->cons = $cons;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function setIsVisible(bool $is_visible): void
    {
        $this->is_visible = $is_visible;
    }

}