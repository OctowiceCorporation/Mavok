<?php


namespace App\Mappers;


use App\DTO\Product as ProductDto;
use App\Entity\Product as ProductEntity;

class Product
{
    public function entityToDto(ProductEntity $entity): ProductDto
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
            $entity->getDescription(),
            $entity->getWholesalePrice(),
            $entity->getManufacturer(),
            $entity->getProducingCountry(),
            $entity->getMinimumWholesale(),
            $entity->getSale(),
            $entity->getCurrency()->getId(),
            $entity->getProductUnit()
        );
    }
}