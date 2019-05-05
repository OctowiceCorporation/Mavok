<?php


namespace App\Mappers;


use App\DTO\NovaPoshtaCity as NovaPoshtaCityDto;
use App\Entity\NovaPoshtaCity as NovaPoshtaCityEntity;

class NovaPoshtaCity
{
    public function entityToDto(NovaPoshtaCityEntity $entity): NovaPoshtaCityDto
    {
        return new NovaPoshtaCityDto(
            $entity->getName()
        );
    }
}