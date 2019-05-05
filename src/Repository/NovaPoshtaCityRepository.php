<?php

namespace App\Repository;

use App\Entity\NovaPoshtaCity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NovaPoshtaCity|null find($id, $lockMode = null, $lockVersion = null)
 * @method NovaPoshtaCity|null findOneBy(array $criteria, array $orderBy = null)
 * @method NovaPoshtaCity[]    findAll()
 * @method NovaPoshtaCity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NovaPoshtaCityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NovaPoshtaCity::class);
    }

    // /**
    //  * @return NovaPoshtaCity[] Returns an array of NovaPoshtaCity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NovaPoshtaCity
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
