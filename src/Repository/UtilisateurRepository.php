<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Utilisateur[]    findByEmailAndPassword(sting email,sring password)
 * @method Utilisateur[]    findByEmail(sting email)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    // /**
    //  * @return Utilisateur[] Returns an array of Utilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findByEmailAndPassword($email,$password)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.email = :email')
            ->andWhere('g.mdp = :mdp')
            ->setParameter('email', $email)
            ->setParameter('mdp', $password)
            ->groupBy('g.nom')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByEmail($email)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.email = :email')
            ->setParameter('email', $email)
            ->groupBy('g.nom')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
