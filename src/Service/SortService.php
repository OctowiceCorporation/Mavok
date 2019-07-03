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
                        $firstRetail = $a->getRetailPrice();
                        $secondRetail = $b->getRetailPrice();
                        if(!empty($a->getSale()))
                            $a->setRetailPrice($a->getRetailPrice()-($a->getRetailPrice()*$a->getSale()/100));
                        if(!empty($b->getSale()))
                            $b->setRetailPrice($b->getRetailPrice() - ($b->getRetailPrice()*$b->getSale()/100));


                        if(!empty($a->getProductValue()))
                            $first = $a->getProductValue()*$a->getRetailPrice();
                        else
                            $first = $a->getRetailPrice();

                        if(!empty($b->getProductValue()))
                            $second = $b->getProductValue()*$b->getRetailPrice();
                        else
                            $second = $b->getRetailPrice();

                        $a->setRetailPrice($firstRetail);
                        $b->setRetailPrice($secondRetail);




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
                    else{
                        $firstRetail = $a->getRetailPrice();
                        $secondRetail = $b->getRetailPrice();
                        if(!empty($a->getSale()))
                            $a->setRetailPrice($a->getRetailPrice()-($a->getRetailPrice()*$a->getSale()/100));
                        if(!empty($b->getSale()))
                            $b->setRetailPrice($b->getRetailPrice() - ($b->getRetailPrice()*$b->getSale()/100));


                        if(!empty($a->getProductValue()))
                            $first = $a->getProductValue()*$a->getRetailPrice();
                        else
                            $first = $a->getRetailPrice();

                        if(!empty($b->getProductValue()))
                            $second = $b->getProductValue()*$b->getRetailPrice();
                        else
                            $second = $b->getRetailPrice();

                        $a->setRetailPrice($firstRetail);
                        $b->setRetailPrice($secondRetail);
//
                        return $first > $second;
                    }
                });
                break;
                break;
        }

    }

}