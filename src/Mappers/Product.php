<?php


namespace App\Mappers;


use App\DTO\Product as ProductDto;
use App\Entity\Product as ProductEntity;

class Product
{
    static function entityToDto(ProductEntity $entity, float $value = null): ProductDto
    {
        return new ProductDto(
            $entity->getCategory()->getId(),
            $entity->getName(),
            $entity->getImages()->get(0)->getImagePath(),
            $entity->getRetailPrice(),
            $entity->getCurrencyName(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getIsAvailable(),
            $entity->getIsVisible(),
            $entity->getSpecialOffer(),
            $entity->getDescription(),
            $entity->getWholesalePrice(),
            $entity->getBrand()->getName(),
            $entity->getBrand()->getCountry(),
            $entity->getMinimumWholesale(),
            $entity->getSale(),
            $value,
            $entity->getProductUnit()
        );
    }
}