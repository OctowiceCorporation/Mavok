<?php


namespace App\Mappers;


use App\DTO\NovaPoshtaPostOffice as NovaPoshtaPostOfficeDto;
use App\Entity\NovaPoshtaPostOffice as NovaPoshtaPostOfficeEntity;

class NovaPoshtaPostOffice
{
    public function entityToDto(NovaPoshtaPostOfficeEntity $entity): NovaPoshtaPostOfficeDto
    {
        return new NovaPoshtaPostOfficeDto(
            $entity->getNumber(),
            $entity->getAddress(),
            $entity->getCity()->getId()
        );
    }
}