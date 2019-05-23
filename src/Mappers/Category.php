<?php


namespace App\Mappers;


use App\DTO\Category as CategoryDto;
use App\Entity\Category as CategoryEntity;

class Category
{
    static function entityToDto(CategoryEntity $entity): CategoryDto
    {
        return new CategoryDto(
            $entity->getName(),
            $entity->getIsVisible(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getSlug(),
            $entity->getParent() ? $entity->getParent()->getId() : null,
            $entity->getDescription(),
            $entity->getImage()
        );
    }
}