<?php


namespace App\Service;


class SortService
{
    public function sort(string $type, &$products)
    {
        switch ($type){
            case 'name':
                usort($products, function($a, $b)
                {
                    if($a->isIsAvailable() < $b->isIsAvailable())
                        return true;
                    elseif($a->isIsAvailable() > $b->isIsAvailable())
                        return false;
                    else
                        return strnatcmp($a->getName(), $b->getName());
                });
                break;
            case 'date':
                usort($products, function($a, $b)
                {
                    if($a->isIsAvailable() < $b->isIsAvailable())
                        return true;
                    elseif($a->isIsAvailable() > $b->isIsAvailable())
                        return false;
                    else
                        return $a->getCreatedAt() < $b->getCreatedAt();
                });
                break;
            case 'price_down':
                usort($products, function($a, $b)
                {
                    if($a->isIsAvailable() < $b->isIsAvailable())
                        return true;
                    elseif($a->isIsAvailable() > $b->isIsAvailable())
                        return false;
                    else{
                        if(!empty($a->getProductValue()))
                            $first = $a->getProductValue()*$a->getRetailPrice();
                        else
                            $first = $a->getRetailPrice();

                        if(!empty($b->getProductValue()))
                            $second = $b->getProductValue()*$b->getRetailPrice();
                        else
                            $second = $b->getRetailPrice();

                        return $first < $second;
                    }
                });
                break;
            case 'price_up':
                usort($products, function($a, $b)
                {
                    if($a->isIsAvailable() < $b->isIsAvailable())
                        return true;
                    elseif($a->isIsAvailable() > $b->isIsAvailable())
                        return false;
                    else {
                        if (!empty($a->getProductValue()))
                            $first = $a->getProductValue() * $a->getRetailPrice();
                        else
                            $first = $a->getRetailPrice();

                        if (!empty($b->getProductValue()))
                            $second = $b->getProductValue() * $b->getRetailPrice();
                        else
                            $second = $b->getRetailPrice();
//
                        return $first > $second;
                    }
                });
                break;
                break;
        }

    }

}