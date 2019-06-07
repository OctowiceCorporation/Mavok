<?php


namespace App\DTO;


class CategoryForm
{
    private $name;
    private $description;
    private $image;
    private $usd;
    private $eur;

    /**
     * CategoryForm constructor.
     * @param $name
     * @param $description
     * @param $image
     * @param $usd
     * @param $eur
     */
    public function __construct($name, $description, $usd, $eur, $image = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->usd = $usd;
        $this->eur = $eur;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return CategoryForm
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return CategoryForm
     */
    public function setDescription($description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return CategoryForm
     */
    public function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsd()
    {
        return $this->usd;
    }

    /**
     * @param mixed $usd
     * @return CategoryForm
     */
    public function setUsd($usd): self
    {
        $this->usd = $usd;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEur()
    {
        return $this->eur;
    }

    /**
     * @param mixed $eur
     * @return CategoryForm
     */
    public function setEur($eur): self
    {
        $this->eur = $eur;
        return $this;
    }


}