<?php

namespace App\Repository;

use App\Entity\Liaison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Liaison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liaison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liaison[]    findAll()
 * @method Liaison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Liaison[]    findByOrderToDate()
 * @method Liaison[]    findById(int $id)
 */
class LiaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liaison::class);
    }

    // /**
    //  * @return Liaison[] Returns an array of Liaison objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findByOrderToDate(): array
    {
        return $this->createQueryBuilder('h')
            ->innerJoin('h.idProduit', 't')
            ->orderBy('t.date', 'ASC')
            ->setMaxResults(4)
            ->groupBy('h.idpack')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findById($id): array
    {
        return $this->createQueryBuilder('h')
            ->innerJoin('h.idProduit', 'p')
            ->innerJoin('h.idpack', 't')
            ->andWhere('t.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Liaison
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
