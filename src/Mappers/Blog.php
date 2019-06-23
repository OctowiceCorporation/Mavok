<?php


namespace App\Mappers;


use App\DTO\Blog as BlogDto;
use App\DTO\BlogFormDTO;
use App\Entity\Blog as BlogEntity;

class Blog
{
    static public function entityToDto(BlogEntity $entity): BlogDto
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
    
    static public function entityToFormDto(BlogEntity $entity): BlogFormDTO
    {
        return new BlogFormDTO(
          $entity->getTitle(),
          $entity->getDescription(),
          $entity->getIsVisible()
        );
    }
}