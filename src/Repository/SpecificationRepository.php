<?php

namespace App\Repository;

use App\Entity\Specification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Specification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specification[]    findAll()
 * @method Specification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Specification::class);
    }

    public function getSpecificationFromCategory(int $id)
    {
        return $this->createQueryBuilder('s')
            ->select('s.name','s.unit','s.value', 's.id')
            ->leftJoin('s.product', 'p')->addSelect('p.name AS product_name')
            ->leftJoin('p.brand', 'b')->addSelect('b.country')->addSelect('b.name AS manufacturer')
            ->leftJoin('p.category', 'c')->addSelect('c.name AS category_name')->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();
    }

    public function getProductsFromFilter($filter)
    {
        $builder = $this->createQueryBuilder('s');
        $builder->leftJoin('s.product', 'p');
        foreach ($filter as $key =>  $item) {
            $builder->setParameter('values', $item['values']);
            $builder->setParameter('name', $item['name']);

            $builder->andWhere("s.name = :name")
                ->andWhere('s.value IN (:values) ');
        }
        return $builder->getQuery()->getResult();
    }

    // /**
    //  * @return Specification[] Returns an array of Specification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specification
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
