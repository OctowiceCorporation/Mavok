<?php


namespace App\Service;


use App\Entity\Category;
use App\Mappers\Category as CategoryMapper;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CategoryService
{
    private $categoryRepository;
    private $productService;
    private $session;

    public function __construct(CategoryRepository $categoryRepository, ProductService $productService, SessionInterface $session)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productService = $productService;
        $this->session = $session;
    }

    private function callbackGetProducts(Category $category, array &$array)
    {
        if($category->getProducts()->isEmpty()){
            foreach ($category->getChildren() as $child) {
                $this->callbackGetProducts($child, $array);
            }
        }
        else
            foreach ($category->getProducts() as $product) {
                $array[] = $product;
            }
    }

    public function getChildProducts(Category $category)
    {
        $arr = [];
        foreach ($category->getChildren() as $child) {
            $this->callbackGetProducts($child, $arr);
        }

        return $arr;
    }

    public function generateUrlFromCategory(Category $category): string
    {
        $url = '';
        $parent = $category;
        while (!empty($parent)){
            $url = $parent->getSlug().'/'.$url;

            $parent = $parent->getParent();
        }

        return '/'.$url;
    }

    public function generateUrlForAllCategories(array $categories): array
    {
        $array = [];

        foreach ($categories as $category) {
            $array[$category->getId()] = [];
            $this->callbackCategory($category, $array[$category->getId()]);
        }

        return $array;
    }

    private function callbackCategory(Category $category, array &$array)
    {
        $array['name'] = $category->getName();
        $array['link'] = $this->generateUrlFromCategory($category);
        $array['is_visible'] = $category->getIsVisible();
        $array['id'] = $category->getId();
        if($category->getProducts()->isEmpty())
            $array['has_products'] = false;
        else
            $array['has_products'] = true;
        $array['sub'] = [];
        if(!$category->getChildren()->isEmpty()){
            foreach ($category->getChildren() as $item) {
                $array['sub'][$item->getId()] = [];
                $this->callbackCategory($item,$array['sub'][$item->getId()]);
            }
        }
    }

    public function getCategories()
    {

        $categories = new ArrayCollection();
        foreach ($this->categoryRepository->findBy(['parent' => null]) as $category) {
            $categories->add(CategoryMapper::entityToDto($category, substr($this->generateUrlFromCategory($category), 1)));
        }
        return $categories;
    }

    public function generateRoute(array $array)
    {
        $count = count($array);
        $result['found'] = false;
        $result['gabela'] = false;
        $result['cat'] = new Category();
        foreach ($array as $key => $item) {
            if($count != $key+1){
                $cat = $this->categoryRepository->findOneBy(['slug' => $item]);

                if(!empty($cat->getParent()) && $key == 0)
                    $result['gabela'] = true;
                if($cat->getChildren()->isEmpty())
                   $result['gabela'] = true;
                foreach ($this->categoryRepository->findOneBy(['slug' => $item])->getChildren() as $child) {
                    if($child->getSlug() == $array[$key+1]){
                        $result['found'] = true;
                        break;
                    }
                }
            }
            if($count == 1) {
                $cat = $this->categoryRepository->findOneBy(['slug' => $item]);
                if(!empty($cat->getParent()))
                    $result['gabela'] = true;
            }
        }
        return $result;
    }

    public function isLastCategory(array $array)
    {
        $last_category = $this->categoryRepository->findOneBy(['slug' => $array[count($array)-1]]);
        $categories = new ArrayCollection();
        $products = new ArrayCollection();
            foreach ($last_category->getChildren() as $child) {
                $categories->add(CategoryMapper::entityToDto($child, substr($this->generateUrlFromCategory($child), 1)));
            }
            $basket = $this->session->get('basket');
            foreach ($this->getChildProducts($last_category) as $childProduct) {
                if($childProduct->getIsVisible()){
                    $amount = 0;
                    if(isset($basket[$childProduct->getId()]))
                        $amount = $basket[$childProduct->getId()];
                    $products->add($this->productService->getProductPrice($childProduct, null, $amount));
                }
            }

        return [$categories,$products];
    }
}