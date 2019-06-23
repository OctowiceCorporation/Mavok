<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class CommonInfoDTO
{
    private $minimum;

    /**
     * @Assert\Regex("/^-?(?:\d+|\d*\.\d+)$/")
     */
    private $eur;

    /**
     * @Assert\Regex("/^-?(?:\d+|\d*\.\d+)$/")
     */
    private $usd;
    private $number;
    private $address;
    private $name;

    /**
     * CommonInfoDTO constructor.
     * @param $minimum
     * @param $eur
     * @param $usd
     * @param $number
     * @param $address
     * @param $name
     */
    public function __construct($minimum, $eur, $usd, $number, $address, $name)
    {
        $this->minimum = $minimum;
        $this->eur = $eur;
        $this->usd = $usd;
        $this->number = $number;
        $this->address = $address;
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @param mixed $minimum
     */
    public function setMinimum($minimum): void
    {
        $this->minimum = $minimum;
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
     */
    public function setEur($eur): void
    {
        $this->eur = $eur;
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
     */
    public function setUsd($usd): void
    {
        $this->usd = $usd;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
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
     */
    public function setName($name): void
    {
        $this->name = $name;
    }



}