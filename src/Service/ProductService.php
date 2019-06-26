<?php


namespace App\Service;


use App\Entity\Category;
use App\Entity\Product;
use App\Mappers\Specification;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class ProductService
{
    private $usd;
    private $eur;

    public function __construct(ContainerInterface $container)
    {
        $file = Yaml::parse(file_get_contents($container->getParameter('kernel.project_dir').'/config/common_info.yaml'));
        $this->usd = $file['usd_value'];
        $this->eur = $file['eur_value'];
    }


    public function getProductsFromCategory(Category $category): ArrayCollection
    {
        $products = new ArrayCollection();
        $this->callbackGetProducts($category, $products);

        return $products;
    }

    private function callbackGetProducts(Category $category, ArrayCollection &$products){
        if(!$category->getProducts()->isEmpty()){
            foreach ($category->getProducts() as $product) {
                $products->add($product);
            }
        }
        if(!$category->getChildren()->isEmpty()){
            foreach ($category->getChildren() as $child) {
                $this->callbackGetProducts($child, $products);
            }
        }
    }

    public function getProductPrice(Product $product, $spec = null, int $amount = null): \App\DTO\Product
    {
        $currency = $product->getCurrencyName();
        if ($currency === "UAH")
            return \App\Mappers\Product::entityToDto($product, $spec, null, $amount);
        switch ($currency) {
            case 'USD':
                if (!empty($value = $product->getBrand()->getUsdValue()))
                    return \App\Mappers\Product::entityToDto($product, $spec, $value, $amount);
                break;
            case 'EUR':
                if (!empty($value = $product->getBrand()->getEurValue()))
                    return \App\Mappers\Product::entityToDto($product, $spec, $value, $amount);
                break;

        }
        switch ($currency) {
            case 'USD':
                if (!empty($value = $product->getCategory()->getUsdValue()))
                    return \App\Mappers\Product::entityToDto($product, $spec, $value, $amount);
                break;
            case 'EUR':
                if (!empty($value = $product->getCategory()->getEurValue()))
                    return \App\Mappers\Product::entityToDto($product, $spec, $value, $amount);
                break;
        }
        switch ($currency) {
            case 'USD':
                    return \App\Mappers\Product::entityToDto($product, $spec, $this->usd, $amount);
                break;
            case 'EUR':
                    return \App\Mappers\Product::entityToDto($product, $spec, $this->eur, $amount);
                break;
        }

    }

    public function getProducts(Category $last_category)
    {
        $products = new ArrayCollection();
        foreach ($last_category->getProducts() as $product) {
            if($product->getIsVisible())
                $products->add($this->getProductPrice($product));
        }
        return $products;
    }

    public function getSpecifications(Product $product): ArrayCollection
    {
        $specifications = new ArrayCollection();
        foreach ($product->getSpecifications() as $specification) {
            $specifications->add(Specification::entityToDto($specification));
        }
        return $specifications;
    }

    /**
     * @param Product $product
     * @return Category[]
     */
    public function getParentCategories(Product $product): array
    {
        $category = $product->getCategory();
        $categories = [];
        while(!empty($category)){
            array_unshift($categories, $category);
            $category = $category->getParent();
        }

        return $categories;
    }

}