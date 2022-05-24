<?php

namespace App\Controller;

use App\Entity\Header;
use App\Entity\Produit;
use App\Entity\Gestionstock;
use App\Entity\Produitsphares;
use App\Entity\Liaison;
use App\Entity\Promotion;
use App\Repository\HeaderRepository;
use App\Repository\ProduitRepository;
use App\Repository\GestionStockRepository;
use App\Repository\ProduitsPharesRepository;
use App\Repository\PromotionPharesRepository;
use App\Repository\LiaisonRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MenuController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
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

        //* @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
        $produitPhareList = $this->getProduitPhare($em);
        $packs = $this->getPacks($em);
        $promotionList = $this->getPromotionArticles($em);
        $repository = $em->getRepository(Header::class)->findOrderedByDate();
        $newProduct = $this->getProduit($em);
        
        //$packs = $em->getRepository(Liaison::class)->findByOrderToDate();
        //dd($promotionList);
        //dd( $this->getPromotionArticles($em));
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'slider' => $repository,
            'newProduct' => $newProduct,
            'produitPhare' => $produitPhareList,
            'packs' => $packs,
            'promotionList' => $promotionList,
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson()
        ]);
    }

    public function getProduit($em):array
    {
        $newProduct = [];
        $produit = $em->getRepository(Gestionstock::class)->findByProductDateAll();
        $save = "";
        $saveRank = -1;
        foreach($produit as $prod){
            if($save != $prod->getIdProduit()){
                array_push($newProduct,[[$prod ],$em->getRepository(Promotion::class)-> findByid($prod->getIdProduit())]);
                $saveRank = $saveRank + 1;
            }else{
                array_push($newProduct[$saveRank][0],$prod);
            }

            $save = $prod->getIdProduit();
        }
        return $newProduct;
    }

    public function getPromotionArticles($em):array
    {
        $promotionList = [];
        $promotions = $em->getRepository(Promotion::class)->findByPromoLimit();
        foreach($promotions as $promo){
            $gestionRetreive = $em->getRepository(Gestionstock::class)->findByProductDateWithProductId($promo->getIdProduit()->getId());
            if($gestionRetreive != []){
                array_push($promotionList,[$gestionRetreive,$promo]);
            }
        }
        return $promotionList;
    }

    //Modifier
    public function getPacks($em):array
    {
        $newProduct = [];
        $produit = $em->getRepository(Gestionstock::class)->findByPacks();
        $save = "";
        $saveRank = -1;
        foreach($produit as $prod){
            if($save != $prod->getIdProduit()){
                array_push($newProduct,[[$prod ],$em->getRepository(Promotion::class)->findByid($prod->getIdProduit())]);
                $saveRank = $saveRank + 1;
            }else{
                array_push($newProduct[$saveRank][0],$prod);
            }

            $save = $prod->getIdProduit();
        }
        return $newProduct;
    }

    public function getProduitPhare($em):array
    {
        $produitPhareList = [];
        $produitPhareListFinal = [];
        $produitPhare = $em->getRepository(Produitsphares::class)->findLimit();
        foreach($produitPhare as $roow){
            array_push($produitPhareList,[$em->getRepository(Gestionstock::class)->findByProductDateWithProductId($roow->getIdProduit())]);
        }
        $save = "";
        $saveRank = -1;
        for($i=0; $i < count($produitPhareList); $i++) {
            if($produitPhareList[$i][0] != []){
                if($save != $produitPhareList[$i][0][0]->getIdProduit()){
                    array_push($produitPhareListFinal,[$produitPhareList[$i][0],$em->getRepository(Promotion::class)-> findByid($produitPhareList[$i][0][0]->getIdProduit())]);
                    $saveRank = $saveRank + 1;
                }else{
                    array_push($produitPhareListFinal[$saveRank][1],$produitPhareList[$i]);
                }
            }
        }
        return $produitPhareListFinal;
    }
}
