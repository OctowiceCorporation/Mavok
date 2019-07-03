<?php


namespace App\Service;


use App\Entity\Category;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use App\Repository\SpecificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FilterService
{

    private $specificationRepository;
    private $product_repository;
    private $productService;
    private $brandRepository;
    private $sortService;
    private $session;

    /**
     * FilterService constructor.
     * @param SpecificationRepository $specificationRepository
     * @param ProductRepository $productRepository
     * @param ProductService $productService
     * @param BrandRepository $brandRepository
     * @param SortService $sortService
     * @param SessionInterface $session
     */
    public function __construct(SpecificationRepository $specificationRepository, ProductRepository $productRepository, ProductService $productService, BrandRepository $brandRepository, SortService $sortService, SessionInterface $session)
    {
        $this->specificationRepository = $specificationRepository;
        $this->product_repository = $productRepository;
        $this->productService = $productService;
        $this->brandRepository = $brandRepository;
        $this->sortService = $sortService;
        $this->session = $session;
    }


    public function getSpecificationsFromCategory(Category $category): array
    {
        $specifications = [];
        $special = ['name' => 'Товары с акцией', 'is_countable' => null, 'values' => ['да' => 'да']];
        $manuf = ['name' => 'Производитель', 'is_countable' => null, 'values' => []];
        $country = ['name' => 'Страна производитель', 'is_countable' => null, 'values' => []];
        foreach($this->specificationRepository->getSpecificationFromCategory($category->getId()) as $item){
            if(!empty($item['manufacturer']))
                $manuf['values'][mb_strtolower($item['manufacturer'])] = mb_strtolower($item['manufacturer']);
            if(!empty($item['country']))
                $country['values'][mb_strtolower($item['country'])] = mb_strtolower($item['country']);
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


        $specifications = [$special['name'] => $special, $manuf['name'] => $manuf, $country['name'] => $country] + $specifications;

        return array_values($specifications);
    }

    public function getProductsFromFilter(array $filter, int $id)
    {
        return $this->product_repository->getProductsFromFilter($filter, $id);
    }

    public function buildFilter(Category $last_category)
    {
        $filter= $this->getSpecificationsFromCategory($last_category);
            foreach ($filter as &$item) {
                if(!empty($item['is_countable'])){
                    foreach ($item['values'] as &$value) {
                        $value = preg_replace('/[^\\d.]+/', '', $value);
                    }
                    $item['min'] = min($item['values']);
                    $item['max'] = max($item['values']);
                }
            }
            foreach ($filter as $key =>$item) {
                if (count($item['values']) == 1 && $item['name'] !== 'Товары с акцией') {
                    unset($filter[$key]);
                }
            }
            $filter = array_map("unserialize", array_unique(array_map("serialize", $filter)));
            return array_values($filter);
    }

    public function isSubmited(array $data, array $filter, Category $last_category, $pagination ,$sort)
    {
        if(empty($pagination))
            $pagination = 1;
        $onlySale = false;
        foreach ($filter as $index => $item) {
            if(!is_array($data[$index]))
                $filter[$index]['values'] = explode(';',$data[$index]);
            else
                $filter[$index]['values'] = $data[$index];
            if(empty($filter[$index]['values']) || empty($filter[$index]['values'][0]) || (isset($filter[$index]['min']) &&$filter[$index]['values'][0] == $filter[$index]['min'] && $filter[$index]['values'][1] == $filter[$index]['max']))
                unset($filter[$index]);
            elseif($filter[$index]['name'] === 'Товары с акцией'){
                unset($filter[$index]);
                $onlySale = true;
            }
        }

        $result = $last_category->getProducts()->toArray();
        $arr = [];
        $basket = $this->session->get('basket');
        foreach ($result as $item) {
            if($item->getIsVisible()){
                if($onlySale){
                    if(!empty($item->getSale())){
                        $amount = 0;
                        if(isset($basket[$item->getId()]))
                            $amount = $basket[$item->getId()];
                        $arr[] = $this->productService->getProductPrice($item, true, $amount);
                    }
                }
                else{
                    $amount = 0;
                    if(isset($basket[$item->getId()]))
                        $amount = $basket[$item->getId()];
                    $arr[] = $this->productService->getProductPrice($item, true, $amount);
                }
            }
        }
        if(!empty($sort))
            $this->sortService->sort($sort, $arr);
        else{
            usort($arr, function($a, $b)
            {
                return $a->isIsAvailable() < $b->isIsAvailable();
            });
        }
        $products = new ArrayCollection();

        foreach ($arr as $product) {
                $found = true;
                $spec = [];
            if(sizeof($products) <= $pagination*20){
                foreach ($product->getSpecifications() as $specification) {
                    $spec[$specification->getName()] = $specification->getValue();
                }
                $spec['Производитель'] = $product->getBrand()->getName();
                $spec['Страна производитель'] = $product->getBrand()->getCountry();


                foreach ($filter as $item) {
                    if(empty($item['is_countable'])) {

                        if (!isset($spec[$item['name']]) || !in_array(mb_strtolower($spec[$item['name']]), $item['values'])) {
                            $found = false;
                            break;
                        }
                    }
                    else{
                        if(!isset($spec[$item['name']]) || ($spec[$item['name']] < $item['values'][0] || $spec[$item['name']] > $item['values'][1])){
                            $found = false;
                            break;
                        }
                    }
                }
                if($found) {
                    $products->add($product);
                }
            }

        }

        return $products;
    }
}