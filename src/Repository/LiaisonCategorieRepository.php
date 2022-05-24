<?php

namespace App\Repository;

use App\Entity\Liaisoncategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Liaisoncategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liaisoncategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liaisoncategorie[]    findAll()
 * @method Liaisoncategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiaisonCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liaisoncategorie::class);
    }

    // /**
    //  * @return Liaisoncategorie[] Returns an array of Liaisoncategorie objects
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
    public function findByAllOne()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idCategorieSecondaire', 't')
            ->innerJoin('g.idCategorieTercaire', 'd')
            ->innerJoin('t.cat', 'l')
            ->select('l.id,t.nom as catdeux,d.nom as cattrois')
            ->orderBy('t.cat', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySecTerc()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idCategorieSecondaire', 't')
            ->innerJoin('g.idCategorieTercaire', 'd')
            ->innerJoin('t.cat', 'l')
            ->select('t.id,t.nom,d.nom as terc')
            ->orderBy('t.cat', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Liaisoncategorie
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
