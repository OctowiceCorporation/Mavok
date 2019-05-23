<?php


namespace App\Service;


use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\SpecificationRepository;
use Doctrine\Common\Collections\ArrayCollection;

class FilterService
{

    private $specificationRepository;
    private $product_repository;

    /**
     * FilterService constructor.
     * @param $specificationRepository
     */
    public function __construct(SpecificationRepository $specificationRepository, ProductRepository $productRepository)
    {
        $this->specificationRepository = $specificationRepository;
        $this->product_repository = $productRepository;
    }


    public function getSpecificationsFromCategory(Category $category): ArrayCollection
    {
        $specifications = [];
        foreach($this->specificationRepository->getSpecificationFromCategory($category->getId()) as $item){
            if(!isset($specifications[$item['name']])){
                $specifications[$item['name']] = [];
                $specifications[$item['name']]['name'] = $item['name'];
                $specifications[$item['name']]['is_countable'] = $item['unit'];
            }
            $specifications[$item['name']]['values'][mb_strtolower($item['value'])] = mb_strtolower($item['value']);
        }
        foreach ($specifications as $key => $specification) {
            $specifications[$key]['values'] = array_unique($specifications[$key]['values']);
        }
        return new ArrayCollection(array_values($specifications));
    }

    public function getProductsFromFilter(array $filter, int $id)
    {
        return $this->product_repository->getProductsFromFilter($filter, $id);
    }
}