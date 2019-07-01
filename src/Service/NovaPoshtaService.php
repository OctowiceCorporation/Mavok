<?php


namespace App\Service;


use App\Repository\NovaPoshtaCityRepository;

class NovaPoshtaService
{
    private $cityRepository;

    public function __construct(NovaPoshtaCityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function getJson()
    {
        $arr = [];
        $cities = $this->cityRepository->findBy([],['name' => 'ASC']);
        foreach ($cities as $key => $city) {
            $arr[$key] = [];
            $arr[$key]['city'] = $city->getName();
            $arr[$key]['post_offices'] = [];
            foreach ($city->getNovaPoshtaPostOffices() as $keyq => $novaPoshtaPostOffice) {
                $arr[$key]['post_offices'][$keyq]['address'] = $novaPoshtaPostOffice->getAddress();
                $arr[$key]['post_offices'][$keyq]['number'] = $novaPoshtaPostOffice->getNumber();
            }
        }
        return $arr;
    }
}