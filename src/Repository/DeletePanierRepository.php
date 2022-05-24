<?php

namespace App\Repository;

use App\Entity\DeletePanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeletePanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeletePanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeletePanier[]    findAll()
 * @method DeletePanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method DeletePanier[]    findByIdClient(int $value)findAllIdPanier()
 * @method DeletePanier[]    findAllIdPanier()
 */
class DeletePanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeletePanier::class);
    }

    // /**
    //  * @return DeletePanier[] Returns an array of DeletePanier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findAllIdPanier()
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.idPanier', 'k')
            ->select('k.id')
            ->groupBy('d.idPanier')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?DeletePanier
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
