<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Promotion[]    findByPromoLimit().
 * @method Promotion[]    findByPromoNoLimit()
 * @method Promotion[]    findByid(int $id)
 * @method Promotion[]    findByidProduct(int $id)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    // /**
    //  * @return Promotion[] Returns an array of Promotion objects
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

    public function findByPromoLimit(): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.dateFin > :date')
            ->setParameter('date', date('Y-m-d'))
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPromoNoLimit(): array
    {
        return $this->createQueryBuilder('h')
            ->innerJoin('h.idProduit', 't')
            ->andWhere('h.dateFin > :date')
            ->setParameter('date', date('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByid($id): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.idProduit = :val')
            ->andWhere('h.dateFin > :date')
            ->setParameter('val', $id)
            ->setParameter('date', date('Y-m-d'))
            ->setMaxResults(1)
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
    public function findOneBySomeField($value): ?Promotion
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
