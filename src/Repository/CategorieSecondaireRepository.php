<?php

namespace App\Repository;

use App\Entity\CategorieSecondaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieSecondaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieSecondaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieSecondaire[]    findAll()
 * @method CategorieSecondaire[]    findAllOrderByAsc()
 * @method CategorieSecondaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method CategorieSecondaire[]    findByIdPrimaryCategorie(int $id)
 */
class CategorieSecondaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieSecondaire::class);
    }

    // /**
    //  * @return CategorieSecondaire[] Returns an array of CategorieSecondaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findByIdPrimaryCategorie($id)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.cat = :val')
            ->setParameter('val', $id)
            ->groupBy('g.nom')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllOrderByAsc()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.nom', 'ASC')
            ->groupBy('g.nom')
            ->getQuery()
            ->getResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?CategorieSecondaire
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
