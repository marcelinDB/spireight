<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Panier[]    findByIdClient(int $value)
 */
class panierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    // /**
    //  * @return Panier[] Returns an array of Panier objects
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

    public function findByIdClient($value,$arr)
    {
        if(count($arr) == 0){
            $arr = [1000000000000000];
        }
        return $this->createQueryBuilder('p')
            ->innerJoin('p.idGestionstock', 't')
            ->innerJoin('t.idType', 'l')
            ->innerJoin('t.idProduit', 'k')
            ->select('p.id as Primary,p.quantite as userQuantity,t.id,k.id as idprod,t.quantite,t.prix,k.nom,k.img,l.nomType,p.jour')
            ->andWhere('p.id NOT IN (:ids)')
            ->andWhere('p.idUsers = :val')
            ->setParameter('val', $value)
            ->setParameter('ids', $arr)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Panier
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
