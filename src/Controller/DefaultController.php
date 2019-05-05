<?php


namespace App\Controller;



use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\Image;
use App\Entity\NovaPoshtaCity;
use App\Entity\NovaPoshtaPostOffice;
use App\Entity\Product;
use App\Entity\Specification;
use App\Repository\CategoryRepository;
use App\Repository\NovaPoshtaCityRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use GuzzleHttp\Client;
use Symfony\Component\Config\Definition\Exception\Exception;

class DefaultController
{

    public function index(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findBy(['parent' => null]);

        function callback(Category $children, &$arr){
            $prod = [];
            foreach ($children->getProducts() as $product) {
                $prod[] = $product->getName();
                dump($children);
            }
            $arr[] = [$children->getName(), $prod];
            foreach ($children->getChildren() as $child) {
                callback($child, $arr);
            }
        }

        $arr = [];

        foreach ($categories as $category) {
            callback($category, $arr);
        }

        echo '<pre>';
            print_r($arr);
        echo '</pre>';

        die();
    }

    public function parceNP(EntityManagerInterface $em)
    {
        $string = file_get_contents('https://novaposhta.ua/shop/office/getjsonwarehouselist');
        $array = json_decode($string, true);
        $array = $array['response'];
        $cities = [];
        foreach ($array as $item) {
            if($item['warehouseTypeDescription'] == 'Поштове відділення' ||$item['warehouseTypeDescription'] == 'Вантажне відділення'){
                if(!(array_key_exists($item['city'],$cities))){
                    $city = new NovaPoshtaCity();
                    $city->setName($item['city']);
                    $em->persist($city);
                    $em->flush();
                    $cities[$item['city']] = $city;
                }

                $postOffice = new NovaPoshtaPostOffice();
                $postOffice
                    ->setCity($cities[$item['city']])
                    ->setAddress($item['address'])
                    ->setNumber($item['number']);
                $em->persist($postOffice);
            }
        }

        $em->flush();
        dd($cities);
    }

    public function addCategory(EntityManagerInterface $em)
    {
        $client = new Client();

        $currencies = $em->getRepository(Currency::class)->findAll();
        $currencies_array = [];

        foreach ($currencies as $currency) {
            $currencies_array[$currency->getName()] = $currency;
        }

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load('import/BoilerCategories.xlsx');
        $categories = $spreadsheet->getActiveSheet()->toArray();
        array_shift($categories);

        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load('import/BoilerProducts.xlsx');
        $products = $spreadsheet->getActiveSheet()->toArray();
        array_shift($products);


        $root = [];

        foreach ($categories as $key => $category) {
            if($category[2] == null){
                $rootCategory = new Category();
                $rootCategory
                    ->setName($category[1]);
                $em->persist($rootCategory);
                $em->flush();
                $root[$category[0]] = [$category[0], $category[1], $category[2], []];
                unset($categories[$key]);
                foreach ($categories as $index => $subcategory) {
                    if($subcategory[2] == $category[0]){
                        $root[$subcategory[2]][3][$subcategory[0]] = [$subcategory[0], $subcategory[1], $subcategory[2], []];
                        unset($categories[$index]);
                        $subCategoryRow = new Category();
                        $subCategoryRow
                            ->setParent($rootCategory)
                            ->setName($subcategory[1]);
                        $em->persist($subCategoryRow);
                        $em->flush();
                        foreach ($categories as $i => $item) {
                            if($item[2] == $subcategory[0]){
                                $root[$subcategory[2]][3][$subcategory[0]][3][$item[0]] = [$item[0], $item[1], $item[2], []];
                                unset($categories[$i]);
                                $subsubCategoryRow = new Category();
                                $subsubCategoryRow
                                    ->setParent($subCategoryRow)
                                    ->setName($item[1]);
                                $em->persist($subsubCategoryRow);
                                $em->flush();
                                foreach ($categories as $q => $last) {
                                    if($last[2] == $item[0]){
                                        $root[$subcategory[2]][3][$subcategory[0]][3][$item[0]][3][$last[0]] = [$last[0], $last[1], $last[2], []];
                                        unset($categories[$q]);
                                        $lastCategoryRow = new Category();
                                        $lastCategoryRow
                                            ->setParent($subsubCategoryRow)
                                            ->setName($last[1]);
                                        $em->persist($lastCategoryRow);
                                        $em->flush();
                                        if(empty($root[$subcategory[2]][3][$subcategory[0]][3][$item[0]][3][$last[0]][3])){
                                            foreach ($products as $indexqqq => $product) {
                                                if($product[14] == $last[0]){
                                                    $root[$subcategory[2]][3][$subcategory[0]][3][$item[0]][3][$last[0]][3][] = $product;

                                                    $newProduct1 = new Product();
                                                    $newProduct1
                                                        ->setDescription($product[3])
                                                        ->setName($product[1])
                                                        ->setCategory($lastCategoryRow)
                                                        ->setCurrency($currencies_array[$product[6]])
                                                        ->setProductUnit($product[7]);
                                                    if($product[12] == '-' || $product[12] == '0' || empty($product[12]))
                                                        $newProduct1->setIsAvailable(false);
                                                    else
                                                        $newProduct1->setIsAvailable(true);
                                                    $newProduct1
                                                        ->setManufacturer($product[24])
                                                        ->setProducingCountry($product[25])
                                                        ->setMinimumWholesale($product[10])
                                                        ->setSpecialOffer(false)
                                                        ->setRetailPrice($product[5])
                                                        ->setWholesalePrice($product[9]);

                                                    $em->persist($newProduct1);
                                                    $em->flush();

                                                    try{
                                                        $filename = md5(uniqid($product[11]));
                                                        $client->request('GET', $product[11],['save_to' => 'images/'.$filename]);
                                                        $image = new Image();
                                                        $image
                                                            ->setProduct($newProduct1)
                                                            ->setImagePath($filename);
                                                        $em->persist($image);

                                                    }
                                                    catch (Exception $exception){
                                                        echo $exception->getMessage();
                                                    }

                                                    $counter = 37;
                                                    while($counter != 154){
                                                        if(!empty($product[$counter])){
                                                            $specification = new Specification();
                                                            $specification
                                                                ->setName($product[$counter])
                                                                ->setUnit($product[$counter+1])
                                                                ->setValue($product[$counter+2])
                                                                ->setProduct($newProduct1);
                                                            $em->persist($specification);
                                                        }

                                                        $counter += 3;
                                                    }

                                                    unset($products[$indexqqq]);
                                                }
                                            }
                                        }
                                    }
                                }
                                if(empty($root[$subcategory[2]][3][$subcategory[0]][3][$item[0]][3])){
                                    foreach ($products as $indexqq => $product) {
                                        if($product[14] == $item[0]){
                                            $root[$subcategory[2]][3][$subcategory[0]][3][$item[0]][3][] = $product;

                                            $newProduct2 = new Product();
                                            $newProduct2
                                                ->setDescription($product[3])
                                                ->setName($product[1])
                                                ->setCategory($subsubCategoryRow)
                                                ->setCurrency($currencies_array[$product[6]])
                                                ->setProductUnit($product[7]);
                                            if($product[12] == '-' || $product[12] == '0' || empty($product[12]))
                                                $newProduct2->setIsAvailable(false);
                                            else
                                                $newProduct2->setIsAvailable(true);
                                            $newProduct2
                                                ->setManufacturer($product[24])
                                                ->setProducingCountry($product[25])
                                                ->setMinimumWholesale($product[10])
                                                ->setSpecialOffer(false)
                                                ->setRetailPrice($product[5])
                                                ->setWholesalePrice($product[9]);

                                            $em->persist($newProduct2);
                                            $em->flush();

                                            try{
                                                $filename = md5(uniqid($product[11]));
                                                $client->request('GET', $product[11],['save_to' => 'images/'.$filename]);
                                                $image = new Image();
                                                $image
                                                    ->setProduct($newProduct2)
                                                    ->setImagePath($filename);
                                                $em->persist($image);

                                            }
                                            catch (Exception $exception){
                                                echo $exception->getMessage();
                                            }

                                            $counter = 37;
                                            while($counter != 154){
                                                if(!empty($product[$counter])){
                                                    $specification = new Specification();
                                                    $specification
                                                        ->setName($product[$counter])
                                                        ->setUnit($product[$counter+1])
                                                        ->setValue($product[$counter+2])
                                                        ->setProduct($newProduct2);
                                                    $em->persist($specification);
                                                }

                                                $counter += 3;
                                            }

                                            unset($products[$indexqq]);
                                        }
                                    }
                                }
                            }
                        }
                        if(empty($root[$subcategory[2]][3][$subcategory[0]][3])){
                            foreach ($products as $indexq => $product) {
                                if($product[14] == $subcategory[0]){
                                    $root[$subcategory[2]][3][$subcategory[0]][3][] = $product;

                                    $newProduct3 = new Product();
                                    $newProduct3
                                        ->setDescription($product[3])
                                        ->setName($product[1])
                                        ->setCategory($subCategoryRow)
                                        ->setCurrency($currencies_array[$product[6]])
                                        ->setProductUnit($product[7]);
                                    if($product[12] == '-' || $product[12] == '0' || empty($product[12]))
                                        $newProduct3->setIsAvailable(false);
                                    else
                                        $newProduct3->setIsAvailable(true);
                                    $newProduct3
                                        ->setManufacturer($product[24])
                                        ->setProducingCountry($product[25])
                                        ->setMinimumWholesale($product[10])
                                        ->setSpecialOffer(false)
                                        ->setRetailPrice($product[5])
                                        ->setWholesalePrice($product[9]);

                                    try{
                                        $filename = md5(uniqid($product[11]));
                                        $client->request('GET', $product[11],['save_to' => 'images/'.$filename]);
                                        $image = new Image();
                                        $image
                                            ->setProduct($newProduct3)
                                            ->setImagePath($filename);
                                        $em->persist($image);

                                    }
                                    catch (Exception $exception){
                                        echo $exception->getMessage();
                                    }

                                    $em->persist($newProduct3);
                                    $em->flush();

                                    $counter = 37;
                                    while($counter != 154){
                                        if(!empty($product[$counter])){
                                            $specification = new Specification();
                                            $specification
                                                ->setName($product[$counter])
                                                ->setUnit($product[$counter+1])
                                                ->setValue($product[$counter+2])
                                                ->setProduct($newProduct3);
                                            $em->persist($specification);
                                        }

                                        $counter += 3;
                                    }

                                    unset($products[$indexq]);
                                }
                            }
                        }
                    }
                }
                if(empty($root[$category[0]][3])){
                    foreach ($products as $index => $product) {
                        if($product[14] == $category[0]){
                            $root[$category[0]][3][] = $product;
                            $newProduct4 = new Product();
                            $newProduct4
                                ->setDescription($product[3])
                                ->setName($product[1])
                                ->setCategory($rootCategory)
                                ->setCurrency($currencies_array[$product[6]])
                                ->setProductUnit($product[7]);
                                if($product[12] == '-' || $product[12] == '0' || empty($product[12]))
                                    $newProduct4->setIsAvailable(false);
                                else
                                    $newProduct4->setIsAvailable(true);
                                $newProduct4
                                    ->setManufacturer($product[24])
                                    ->setProducingCountry($product[25])
                                    ->setMinimumWholesale($product[10])
                                    ->setSpecialOffer(false)
                                    ->setRetailPrice($product[5])
                                    ->setWholesalePrice($product[9]);
                                $em->persist($newProduct4);
                                $em->flush();

                                try{
                                    $filename = md5(uniqid($product[11]));
                                    $client->request('GET', $product[11],['save_to' => 'images/'.$filename]);
                                    $image = new Image();
                                    $image
                                        ->setProduct($newProduct4)
                                        ->setImagePath($filename);
                                    $em->persist($image);

                                    }
                                catch (Exception $exception){
                                    echo $exception->getMessage();
                                }

                                $counter = 37;
                                while($counter != 154){
                                    if(!empty($product[$counter])){
                                        $specification = new Specification();
                                        $specification
                                            ->setName($product[$counter])
                                            ->setUnit($product[$counter+1])
                                            ->setValue($product[$counter+2])
                                            ->setProduct($newProduct4);
                                        $em->persist($specification);
                                    }

                                    $counter += 3;
                                }
                            unset($products[$index]);
                        }
                    }
                }
            }
        }

        $em->flush();

        dd($root, $categories, $products);

    }

    public function getNP(NovaPoshtaCityRepository $cityRepository)
    {
        $cities = $cityRepository->findAll();
        dd($cities);
    }
}