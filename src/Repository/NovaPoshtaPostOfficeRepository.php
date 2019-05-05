<?php

namespace App\Repository;

use App\Entity\NovaPoshtaPostOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NovaPoshtaPostOffice|null find($id, $lockMode = null, $lockVersion = null)
 * @method NovaPoshtaPostOffice|null findOneBy(array $criteria, array $orderBy = null)
 * @method NovaPoshtaPostOffice[]    findAll()
 * @method NovaPoshtaPostOffice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NovaPoshtaPostOfficeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NovaPoshtaPostOffice::class);
    }

    // /**
    //  * @return NovaPoshtaPostOffice[] Returns an array of NovaPoshtaPostOffice objects
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
    public function findOneBySomeField($value): ?NovaPoshtaPostOffice
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
