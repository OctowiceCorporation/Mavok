<?php


namespace App\Service;


use App\Entity\Product;

class ProductService
{
    private $usd;
    private $eur;

    public function __construct($usd, $eur)
    {
        $this->usd = $usd;
        $this->eur = $eur;
    }


    public function getProductPrice(Product $product): \App\DTO\Product
    {
        $currency = $product->getCurrencyName();
        if ($currency === "UAH")
            return \App\Mappers\Product::entityToDto($product);
        switch ($currency) {
            case 'USD':
                if (!empty($value = $product->getBrand()->getUsdValue()))
                    return \App\Mappers\Product::entityToDto($product, $value);
                break;
            case 'EUR':
                if (!empty($value = $product->getBrand()->getEurValue()))
                    return \App\Mappers\Product::entityToDto($product, $value);
                break;

        }
        switch ($currency) {
            case 'USD':
                if (!empty($value = $product->getCategory()->getUsdValue()))
                    return \App\Mappers\Product::entityToDto($product, $value);
                break;
            case 'EUR':
                if (!empty($value = $product->getCategory()->getEurValue()))
                    return \App\Mappers\Product::entityToDto($product, $value);
                break;
        }
        switch ($currency) {
            case 'USD':
                    return \App\Mappers\Product::entityToDto($product, $this->usd);
                break;
            case 'EUR':
                    return \App\Mappers\Product::entityToDto($product, $this->eur);
                break;
        }

    }

}