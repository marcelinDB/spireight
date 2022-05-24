<?php

namespace App\Repository;

use App\Entity\Produitsphares;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produitsphares|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produitsphares|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produitsphares[]    findAll()
 * @method Produitsphares[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Produitsphares[]    findLimit()
 * @method Produitsphares[]    findByidProduct(int $id)
 */
class ProduitsPharesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produitsphares::class);
    }

    // /**
    //  * @return Produitsphares[] Returns an array of Produitsphares objects
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

    public function findLimit()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->groupBy('p.idProduit')
            ->setMaxResults(4)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByidProduct($id): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.idProduit = :val')
            ->setParameter('val', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Produitsphares
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
