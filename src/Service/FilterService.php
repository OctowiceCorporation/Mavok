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
    private $productService;

    /**
     * FilterService constructor.
     * @param SpecificationRepository $specificationRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(SpecificationRepository $specificationRepository, ProductRepository $productRepository, ProductService $productService)
    {
        $this->specificationRepository = $specificationRepository;
        $this->product_repository = $productRepository;
        $this->productService = $productService;
    }


    public function getSpecificationsFromCategory(Category $category): array
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
        return array_values($specifications);
    }

    public function getProductsFromFilter(array $filter, int $id)
    {
        return $this->product_repository->getProductsFromFilter($filter, $id);
    }

    public function buildFilter(Category $last_category, $filter)
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
            return $filter;
    }

    public function isSubmited(array $data, array $filter, Category $last_category)
    {
        foreach ($filter as $index => $item) {
            if(!is_array($data[$index]))
                $filter[$index]['values'] = explode(';',$data[$index]);
            else
                $filter[$index]['values'] = $data[$index];
            if(empty($filter[$index]['values']) || empty($filter[$index]['values'][0]))
                unset($filter[$index]);
        }

        $result = $last_category->getProducts();
        $arr = [];
        foreach ($result as $product) {
            $arr[$product->getId()]['name'] = $product->getName();
            foreach ($product->getSpecifications() as $item) {
                $arr[$product->getId()]['specif'][] = $item->getName().' .. '.$item->getValue();
            }
        }
        $products = new ArrayCollection();

        foreach ($result as $product) {
            $found = true;
            $spec = [];
            foreach ($product->getSpecifications() as $specification) {
                $spec[$specification->getName()] = $specification->getValue();
            }

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
                $products->add($this->productService->getProductPrice($product));
            }
        }

        return $products;
    }
}