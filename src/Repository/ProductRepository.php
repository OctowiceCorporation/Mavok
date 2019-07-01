<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $text
     * @param null $sort
     * @return Product[]
     */
    public function searchProducts(string $text = null, $sort = null, $categories = null): iterable
    {
        $query =  $this->createQueryBuilder('p');
        if(!empty($text)){
            $query
                ->where('p.name LIKE :text')
                ->setParameter('text', '%'.$text.'%');
        }
        if(!empty($sort)){
            switch ($sort){
                case 'sale':
                    $query->andWhere('p.sale IS NOT NULL');
                    break;
                case 'top':
                    $query->andWhere('p.special_offer = true');
                    break;
                case 'main':
                    $query->andWhere('p.is_on_main = true');
                    break;
            }
        }

        if(!empty($categories)){
            $query->setParameter('categories', $categories)
                ->andWhere('p.category IN (:categories)');
        }


        return $query->getQuery()->getResult();
    }

    public function getNameAndId()
    {
        return $this->createQueryBuilder('p')
            ->select('p.name', 'p.id')
            ->getQuery()->getResult();
    }

    /**
     * @param string $text
     * @param int $limit
     * @return Product[]
     */
    public function searchProductsWithLimit(string $text, int $limit): iterable
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :text')
            ->setMaxResults($limit)
            ->setParameters([ 'text' => '%'.$text.'%'])
            ->orderBy('p.special_offer')
            ->getQuery()->getResult();
    }

    public function getProductsFromCategory(int $id)
    {
        $builder = $this->createQueryBuilder('p');
        $builder->where('p.category = :id')
            ->setParameter('id', $id)
            ->where('p.category = :id');
        return $builder->getQuery();
    }

    /**
     * @return Product[]
     */
    public function getSpecialProducts()
    {
        return $this->createQueryBuilder('p')
            ->where('p.special_offer = 1')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getRandomProducts()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('RAND()')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function getMainPageProducts()
    {
        return $this->createQueryBuilder('p')
            ->where('p.is_on_main = 1')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[]
     */
    public function getSaleProducts()
    {
        return $this->createQueryBuilder('p')
            ->where('p.sale is not null')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    public function getProductsQuery(int $id = null)
    {
        $query =  $this->createQueryBuilder('p');

        if(!empty($id)){
            $query->where('p.category = :id')
                ->setParameter('id', $id);
        }

        return $query->getQuery();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
