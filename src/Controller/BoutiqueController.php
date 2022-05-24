<?php

namespace App\Controller;

use App\Entity\Header;
use App\Entity\Produit;
use App\Entity\Gestionstock;
use App\Entity\Produitsphares;
use App\Entity\Liaison;
use App\Entity\Promotion;
use App\Entity\Categorie;
use App\Entity\CategorieSecondaire;
use App\Repository\HeaderRepository;
use App\Repository\ProduitRepository;
use App\Repository\GestionStockRepository;
use App\Repository\ProduitsPharesRepository;
use App\Repository\PromotionPharesRepository;
use App\Repository\LiaisonRepository;
use App\Repository\CategorieRepository;
use App\Repository\CategorieSecondaireRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MenuController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;


class BoutiqueController extends AbstractController
{
    #[Route('/boutique', name: 'boutique')]
    public function index(EntityManagerInterface $em,RequestStack $requestStack): Response
    {
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            $statusConnexion = false;
        }
        $categorie = $this->getAllCategorie($em);
        $sousCategorie = $this->getAllSubCategorie($em);
        $sousCategorieArray = $this->setSubCategorieToArray($sousCategorie);
        $produit = $this->getAllProduct($em);
        $product = $produit[0];
        $listPrice = $this->getAllPrice($em,$produit[1]);
        $stock = $this->getAllStock($em);
        $promotion = $this->getAllPromotion($em);
        $categorieJson = $this->setCategorieToArray($categorie);
        $boutique = json_encode($this->getFullTable($em,$product,$listPrice,$stock,$promotion,$sousCategorieArray));
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'categorie' => $categorie,
            'boutique' => $boutique,
            'sousCategorie' => $sousCategorie,
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson()
        ]);
    }

    public function getFullTable($em,$product,$listPrice,$stock,$promotion,$sousCategorie)
    {
        $finalTable = [];
       foreach ($product as $prod) {
           try {
            array_push($finalTable,[$prod[0],$prod[1],$prod[2],$prod[3],$prod[4],$this->getQuantity($prod[0],$stock),$this->getPromo($prod[0],$promotion),$this->getCategorieById($prod[3],$sousCategorie),$this->getPriceById($prod[0],$listPrice),$this->getSubCategorie($prod[3],$sousCategorie)]);
           } catch (\Throwable $th) {
               echo $th;
           }
       }
       return $finalTable;
    }

    public function getQuantity($id,$stock)
    {
        foreach($stock as $store){
            if($store['id'] == $id){
                return $store['quantiteTot'] *1;
            }
        }
        return 0;
    }

    public function getSubCategorie($id,$categorie)
    {
        foreach($categorie as $category){
            if($category[0] == $id){
                return $category[1];
            }
        }
        return 0;
    }

    public function getCategorieById($idCat,$categorie)
    {
        foreach($categorie as $cat){
            if($cat[0] == $idCat){
                return $cat[3];
            }
        }
        return "error";
    }

    public function getPromo($id,$promotion)
    {
        foreach($promotion as $promo){
            if($promo[0] == $id){
                return $promo;
            }
        }
        return [];
    }

    public function getPriceById($id,$listPrice)
    {
        foreach($listPrice as $price){
            $achat = 0;
            $location = 0;
            $occassion = 0;
                if($price[0] == $id){
                    if(count($price[1]) > 1){
                        for ($i=0; $i < count($price[1]); $i++) { 
                            if($price[1][$i][0] == "Location"){
                                $location = $price[1][$i][1];
                            }elseif ($price[1][$i][0] == "Achat") {
                                $achat = $price[1][$i][1];
                            }else{
                                $occassion = $price[1][$i][1];
                            }
                        }
                        return [$achat,$occassion,$location];
                    }else{
                        if($price[1][0][0] == "Location"){
                            $location = $price[1][0][1];
                        }elseif ($price[1][0][0] == "Achat") {
                            $achat = $price[1][0][1];
                        }else{
                            $occassion = $price[1][0][1];
                        }
                        return [$achat,$occassion,$location];
                    }
                }
        }
        return [0,0,0];
    }

    public function getAllCategorie($em)
    {
        return $em->getRepository(Categorie::class)-> findAll();
    }

    public function getAllSubCategorie($em)
    {
        return $em->getRepository(CategorieSecondaire::class)-> findAllOrderByAsc();
    }

    public function getAllProduct($em)
    {
        $listIdProduct = [];
        $productList = [];
        $product = $em->getRepository(Produit::class)->findByOrderToAsc();
        foreach ($product as $produit) {
            array_push($listIdProduct,$produit->getId());
            array_push($productList, [$produit->getId(),$produit->getNom(),$produit->getImg(),$produit->getCat()->getId(),date_format($produit->getDate(),"Y-m-d")]);
        }
        return [$productList,$listIdProduct];
    }

    public function getAllStock($em)
    {
        $stock = $em->getRepository(Gestionstock::class)-> findByAllOne();
        return $stock;
    }

    public function getAllPrice($em,$listIdProduct)
    {
        $listPrice = [];
        $rank = -1;
        foreach ($listIdProduct as $prod) {
            $rank = $rank+1;
            $priceTable = [];
            $price = $em->getRepository(Gestionstock::class)->findByProductInfo($prod);
            foreach ($price as $prix) {
                array_push($priceTable, [$prix->getIdType()->getNomType(),$prix->getPrix()]);
            }
            array_push($listPrice,[$prod,$priceTable]);    
        }
        return $listPrice;
    }

    public function getAllPromotion($em)
    {
        $promotionList = [];
        $promotion = $em->getRepository(Promotion::class)->findByPromoNoLimit();
        foreach ($promotion as $promo) {
            array_push($promotionList, [$promo->getIdProduit()->getId(),$promo->getTaux()]);
        }
        return $promotionList;
    }

    public function setSubCategorieToArray($categorie)
    {
        $categorieList = [];
        foreach ($categorie as $cat) {
            array_push($categorieList, [$cat->getId(),$cat->getNom(),$cat->getDate(),$cat->getCat()->getNom()]);
        }
        return $categorieList;
    }

    public function setCategorieToArray($categorie)
    {
        $categorieList = [];
        foreach ($categorie as $cat) {
            array_push($categorieList, [$cat->getId(),$cat->getNom()]);
        }
        return $categorieList;
    }
}


