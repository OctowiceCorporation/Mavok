<?php


namespace App\Mappers;


use App\DTO\Category as CategoryDto;
use App\Entity\Category as CategoryEntity;

class Category
{
    static function entityToDto(CategoryEntity $entity, string $link = null): CategoryDto
    {
        return new CategoryDto(
            $entity->getName(),
            $entity->getIsVisible(),
            $link,
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getParent() ? $entity->getParent()->getId() : null,
            $entity->getDescription(),
            $entity->getImage()
        );
    }
}