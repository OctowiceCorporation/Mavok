<?php


namespace App\Service;


use App\DTO\Product;

class SortService
{
    public function sort(string $type, &$products)
    {
        switch ($type){
            case 'name':
                usort($products, function(Product $a, Product $b)
                {
                    return strcmp($a->getName(), $b->getName());
                });
                break;
            case 'date':
                usort($products, function(Product $a, Product $b)
                {
                    return $a->getCreatedAt() < $b->getCreatedAt();
                });
                break;
            case 'price_down':
                usort($products, function(Product $a, Product $b)
                {
                    if(!empty($a->getProductValue()))
                        $first = $a->getProductValue()*$a->getRetailPrice();
                    else
                        $first = $a->getRetailPrice();

                    if(!empty($b->getProductValue()))
                        $second = $b->getProductValue()*$b->getRetailPrice();
                    else
                        $second = $b->getRetailPrice();
//
                    return $first < $second;
                });
                break;
            case 'price_up':
                usort($products, function(Product $a, Product $b)
                {
                    if(!empty($a->getProductValue()))
                        $first = $a->getProductValue()*$a->getRetailPrice();
                    else
                        $first = $a->getRetailPrice();

                    if(!empty($b->getProductValue()))
                        $second = $b->getProductValue()*$b->getRetailPrice();
                    else
                        $second = $b->getRetailPrice();
//
                    return $first > $second;
                });
                break;
                break;
        }

    }

}