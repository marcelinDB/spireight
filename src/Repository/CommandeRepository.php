<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findByDate(date $date,int $idUser)
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Commande[]    findByNothing()
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    // /**
    //  * @return Commande[] Returns an array of Commande objects
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

    public function findByDate($date,$idUser)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idPanier', 'p')
            ->innerJoin('p.idUsers', 'u')
            ->innerJoin('p.idGestionstock', 's')
            ->innerJoin('s.idType', 't')
            ->innerJoin('s.idProduit', 'n')
            ->select('g.id as Commande,g.date,p.quantite,p.jour,u.id as UserId,u.nom,u.prenom,n.nom,n.description,s.prix,t.nomType,s.caution')
            ->andWhere('u.id = :val')
            ->andWhere('g.date = :ate')
            ->setParameter('val', $idUser)
            ->setParameter('ate', $date)
            ->groupBy('g.date,s.idProduit')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findById($idUser)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idPanier', 'p')
            ->innerJoin('p.idUsers', 'u')
            ->innerJoin('p.idGestionstock', 's')
            ->innerJoin('s.idProduit', 'n')
            ->innerJoin('g.idStatus', 't')
            ->select('g.date, n.nom, t.status')
            ->andWhere('u.id = :val')
            ->setParameter('val', $idUser)
            ->groupBy('g.date')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNothing()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idPanier', 'p')
            ->innerJoin('p.idUsers', 'u')
            ->innerJoin('p.idGestionstock', 's')
            ->innerJoin('s.idProduit', 'n')
            ->innerJoin('g.idStatus', 't')
            ->select('u.id,g.date, n.nom, t.status')
            ->groupBy('g.date')
            ->orderBy('g.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Commande
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
