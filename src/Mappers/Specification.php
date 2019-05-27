<?php


namespace App\Mappers;


use App\DTO\Specification as SpecificationDto;
use App\Entity\Specification as SpecificationEntity;

class Specification
{
    static function entityToDto(SpecificationEntity $entity): SpecificationDto
    {
        return new SpecificationDto(
            $entity->getName(),
            $entity->getUnit(),
            $entity->getValue(),
            $entity->getProduct()->getId()
        );
    }
}