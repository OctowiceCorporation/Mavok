<?php


namespace App\Mappers;


use App\DTO\Category as CategoryDto;
use App\Entity\Category as CategoryEntity;

class Category
{
    public function entityToDto(CategoryEntity $entity): CategoryDto
    {
        return new CategoryDto(
            $entity->getName(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getIsVisible(),
            $entity->getParent() ? $entity->getParent()->getId() : null,
            $entity->getDescription(),
            $entity->getImage()
        );
    }
}