<?php


namespace App\Mappers;


use App\DTO\Brand as BrandDto;
use App\Entity\Brand as BrandEntity;

class Brand
{
    public function entityToDto(BrandEntity $entity): BrandDto
    {
        return new BrandDto(
            $entity->getName(),
            $entity->getUsdValue(),
            $entity->getEurValue(),
            $entity->getCountry()
        );
    }
}