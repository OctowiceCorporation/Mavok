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
     * @return Product[]
     */
    public function searchProducts(string $text): iterable
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :text')
            ->setParameter('text', '%'.$text.'%')
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
