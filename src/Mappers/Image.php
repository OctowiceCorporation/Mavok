<?php


namespace App\Mappers;


use App\DTO\Image as ImageDto;
use App\Entity\Image as ImageEntity;

class Image
{
    public function entityToDto(ImageEntity $entity): ImageDto
    {
        return new ImageDto(
            $entity->getImagePath(),
            $entity->getProduct()->getId()
        );
    }
}