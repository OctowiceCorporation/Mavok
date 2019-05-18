<?php


namespace App\Mappers;


use App\DTO\Blog as BlogDto;
use App\Entity\Blog as BlogEntity;

class Blog
{
    public function entityToDto(BlogEntity $entity): BlogDto
    {
        return new BlogDto(
            $entity->getTitle(),
            $entity->getDescription(),
            $entity->getImage(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $entity->getIsVisible()
        );
    }
}