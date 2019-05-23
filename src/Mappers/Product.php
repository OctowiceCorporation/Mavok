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
            $entity->getRetailPrice(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getIsAvailable(),
            $entity->getIsVisible(),
            $entity->getSpecialOffer(),
            $entity->getSlug(),
            $entity->getDescription(),
            $entity->getWholesalePrice(),
            $entity->getMinimumWholesale(),
            $entity->getSale(),
            $value,
            $entity->getProductUnit(),
            $entity->getCurrencyName(),
            $entity->getBrand()->getId(),
            $entity->getImages()->get(0)->getImagePath()
        );
    }
}