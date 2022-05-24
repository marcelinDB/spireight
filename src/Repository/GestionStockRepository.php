<?php

namespace App\Repository;

use App\Entity\Gestionstock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gestionstock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gestionstock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gestionstock[]    findAll()
 * @method Gestionstock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Gestionstock[]    findByProductId(int $value)
 * @method Gestionstock[]    findByProductDateAll()
 * @method Produit[]    findByProductDateWithProductId(int $id)
 * @method Produit[]    findProductByCategoryId(int $id)
 * @method Produit[]    findByProductInfo(int $id)
 * @method Produit[]    findByAllOne()
 * @method Produit[]    findByNomProduit(string $nom)
 * @method Produit[]    findProductByCategory()
 * @method Produit[]    findByidProduct(int $id)
 */
class GestionStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gestionstock::class);
    }

    // /**
    //  * @return Gestionstock[] Returns an array of Gestionstock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByProductDateAll()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->andWhere('g.quantite > 0')
            ->orderBy('g.prix', 'ASC')
            ->orderBy('t.date', 'ASC')
            ->groupBy('g.idProduit','g.idType')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByAllOne()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->select('t.id,SUM(g.quantite) as quantiteTot')
            ->orderBy('g.prix', 'ASC')
            ->orderBy('t.date', 'ASC')
            ->groupBy('g.idProduit')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByNomProduit($nom)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idType', 't')
            ->innerJoin('g.idProduit', 'p')
            ->select('t.nomType, g.prix, g.id, g.quantite')
            ->andWhere('p.nom = :val')
            ->setParameter('val', $nom)
            ->groupBy('g.idType')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByProductDateWithProductId($id)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->andWhere('g.quantite > 0')
            ->andWhere('t.id = :val')
            ->setParameter('val', $id)
            ->orderBy('g.prix', 'ASC')
            ->orderBy('t.date', 'ASC')
            ->groupBy('g.idProduit','g.idType')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPacks()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->innerJoin('t.cat', 'c')
            ->andWhere('g.quantite > 0')
            ->andWhere('c.nom LIKE :pack')
            ->setParameter('pack', 'Pack%')
            ->orderBy('t.date', 'DESC')
            ->groupBy('g.idProduit','g.idType')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByProductInfo($id)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->andWhere('t.id = :val')
            ->setParameter('val', $id)
            ->orderBy('g.prix', 'ASC')
            ->orderBy('t.date', 'ASC')
            ->groupBy('g.idProduit','g.idType')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findProductByCategoryId($id)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->andWhere('t.cat = :val')
            ->setParameter('val', $id)
            ->orderBy('t.date', 'ASC')
            ->groupBy('g.idProduit')
            ->setMaxResults(40)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findProductByCategory()
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->orderBy('t.date', 'ASC')
            ->groupBy('g.idProduit')
            ->setMaxResults(40)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllAvailable($id)
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.idProduit', 't')
            ->andWhere('t.cat = :val')
            ->setParameter('val', $id)
            ->orderBy('t.date', 'ASC')
            ->groupBy('g.idProduit')
            ->setMaxResults(40)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByidProduct($id,$idType): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.idProduit = :val')
            ->andWhere('h.idType = :fal')
            ->setParameter('val', $id)
            ->setParameter('fal', $idType)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Gestionstock
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
