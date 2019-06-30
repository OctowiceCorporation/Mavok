<?php


namespace App\Mappers;


use App\DTO\CommonInfoDTO;

class CommonInfo
{
    static function arrayToDto(array $service): CommonInfoDTO
    {
        return new CommonInfoDTO(
            $service['minimal_order_price'],
            $service['eur_value'],
            $service['usd_value'],
            $service['phone_number'],
            $service['address'],
            $service['name_surname'],
            $service['about_us'],
            $service['footer_about_us']
        );
    }

    static function DtoToArray(CommonInfoDTO $commonInfoDTO): array
    {
        $service = [];
        $service['minimal_order_price'] = $commonInfoDTO->getMinimum();
        $service['eur_value'] = $commonInfoDTO->getEur();
        $service['usd_value'] = $commonInfoDTO->getUsd();
        $service['phone_number'] = $commonInfoDTO->getNumber();
        $service['address'] = $commonInfoDTO->getAddress();
        $service['name_surname'] = $commonInfoDTO->getName();
        $service['footer_about_us'] = $commonInfoDTO->getFooterAbout();

        return $service;
    }

}