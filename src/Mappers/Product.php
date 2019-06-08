<?php


namespace App\Mappers;


use App\DTO\Product as ProductDto;
use App\DTO\ProductFormDTO;
use App\Entity\Product as ProductEntity;
use Doctrine\Common\Collections\ArrayCollection;

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

    static function FormDTOToEntity(ProductFormDTO $formDTO): ProductEntity
    {
        return new ProductEntity(
            $formDTO->getName(),
            $formDTO->getDescription(),
            $formDTO->getCurrencyName(),
            $formDTO->getWholesalePrice(),
            $formDTO->getRetailPrice(),
            $formDTO->getIsAvailable(),
            $formDTO->getIsVisible(),
            $formDTO->getSpecialOffer(),
            $formDTO->getProductUnit(),
            $formDTO->getBrand()
        );
    }
}