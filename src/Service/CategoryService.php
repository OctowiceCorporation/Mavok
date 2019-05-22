<?php


namespace App\Service;


use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryService
{
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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
        $array['sub'] = [];
        if(!$category->getChildren()->isEmpty()){
            foreach ($category->getChildren() as $item) {
                $array['sub'][$item->getId()] = [];
                $this->callbackCategory($item,$array['sub'][$item->getId()]);
            }
        }
    }
}