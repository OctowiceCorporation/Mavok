<?php


namespace App\Mappers;


use App\DTO\Currency as CurrencyDto;
use App\Entity\Currency as CurrencyEntity;

class Currency
{
    public function entityToDto(CurrencyEntity $entity): CurrencyDto
    {
        return new CurrencyDto(
            $entity->getName(),
            $entity->getValue()
        );
    }
}