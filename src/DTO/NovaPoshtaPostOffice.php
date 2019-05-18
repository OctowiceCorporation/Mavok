<?php


namespace App\DTO;


class NovaPoshtaPostOffice
{
    private $number;
    private $address;
    private $city_id;

    public function __construct(int $number, string $address, int $city_id)
    {
        $this->number = $number;
        $this->address = $address;
        $this->city_id = $city_id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCityId(): int
    {
        return $this->city_id;
    }
}