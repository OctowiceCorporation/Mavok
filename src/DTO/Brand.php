<?php


namespace App\DTO;


class Brand
{
    private $name;
    private $usd_value;
    private $eur_value;
    private $country;

    public function __construct(string $name = null, float $usd_value = null, float $eur_value = null, string $country = null)
    {
        $this->name = $name;
        $this->usd_value = $usd_value;
        $this->eur_value = $eur_value;
        $this->country = $country;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param float $usd_value
     */
    public function setUsdValue(float $usd_value): void
    {
        $this->usd_value = $usd_value;
    }

    /**
     * @param float $eur_value
     */
    public function setEurValue(float $eur_value): void
    {
        $this->eur_value = $eur_value;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUsdValue(): ?float
    {
        return $this->usd_value;
    }

    public function getEurValue(): ?float
    {
        return $this->eur_value;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }
}