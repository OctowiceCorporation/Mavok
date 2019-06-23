<?php


namespace App\Mappers;


use App\DTO\Product as ProductDto;
use App\DTO\ProductFormDTO;
use App\Entity\Product as ProductEntity;

class Product
{
    static function entityToDto(ProductEntity $entity, $spec = null, float $value = null): ProductDto
    {
        $product = new ProductDto(
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
            $entity->getBrand(),
            $entity->getImages()
        );

        if(!empty($spec)){
            $product->setSpecifications($entity->getSpecifications());
            $product->setCategory($entity->getCategory());
        }


        return $product;
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
            $formDTO->getBrand(),
            $formDTO->getSale(),
            $formDTO->getIsOnMain()
        );
    }

    static function EntityToFormDTO(ProductEntity $product): ProductFormDTO
    {
        $specif = [];
        foreach ($product->getSpecifications() as $key => $specification) {
            $specif[$key] = ['name' => $specification->getName(), 'unit' => $specification->getUnit(), 'value' => $specification->getValue()];
        }
        return new ProductFormDTO(
            $product->getCategory(),
            $product->getName(),
            $product->getWholesalePrice(),
            $product->getRetailPrice(),
            $product->getIsAvailable(),
            $product->getIsVisible(),
            $product->getSpecialOffer(),
            $product->getMinimumWholesale(),
            $product->getProductUnit(),
            $product->getCurrencyName(),
            $product->getBrand(),
            json_encode($specif),
            $product->getSale(),
            $product->getDescription(),
            $product->getIsOnMain()
        );
    }
}