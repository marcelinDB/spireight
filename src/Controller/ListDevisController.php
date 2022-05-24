<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Gestionstock;
use App\Entity\Panier;
use App\Entity\DeletePanier;
use App\Entity\Commande;
use App\Entity\Promotion;
use App\Entity\Status;
use App\Repository\GestionStockRepository;
use App\Repository\DeletePanierRepository;
use App\Repository\PromotionRepository;
use App\Repository\StatusRepository;
use App\Repository\panierRepository;
use App\Repository\CommmandeRepository;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class ListDevisController extends AbstractController
{
    #[Route('/listdevis', name: 'list_devis')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,RequestStack $requestStack): Response
    {
        $statusAdmin = 0;
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(isset($_POST['deconnexion'])){
            $session->remove('email');
            $session->remove('id');
        }
        if(!$session->has('email')){
            $statusConnexion = true;
            echo "<script>window.location.replace('./connexion');</script>";
        }else{
            $statusConnexion = false;
        }
        $articleInfo = $this->getArticle($em);
            try {
                $similary = $this->getRandomProduct($em,$articleInfo);
            } catch (\Throwable $th) {
               // echo "<script>window.location.replace('./boutique');</script>";
            }
            try {
                shuffle($similary);
            } catch (\Throwable $th) {
                //throw $th;
            }
            //Ajout Admin id
            if($session->get('id') != 11 && $session->get('id') != 13){
                $devis = $this->getDevis($em,$session->get('id'));
            }else{
                $statusAdmin = 1;
                $devis = $this->getDevisAdmin($em);
            }
        return $this->render('list_devis/index.html.twig', [
            'controller_name' => 'ListDevisController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'similary' => $similary,
            'devis' => $devis,
            'statusAdmin' => $statusAdmin
        ]);
    }

    public function getArticle($em)
    {
        return $em->getRepository(Gestionstock::class)->findByProductInfo(9);
    }

    public function getRandomProduct($em,$articleInfo)
    {
        $produitList = [];
        $produitListFinal = [];
        $produit = $em->getRepository(Gestionstock::class)->findProductByCategoryId($articleInfo[0]->getIdProduit()->getCat());
        foreach($produit as $roow){
            $gestionRetreive = $em->getRepository(Gestionstock::class)->findByProductDateWithProductId($roow->getIdProduit()->getId());
            if($gestionRetreive != []){
                array_push($produitList,[$gestionRetreive]);
            }
        }
        $save = "";
        $saveRank = -1;
        for($i=0; $i < count($produitList); $i++) { 
            if($save != $produitList[$i][0][0]->getIdProduit()){
                array_push($produitListFinal,[$produitList[$i][0],$em->getRepository(Promotion::class)-> findByid($produitList[$i][0][0]->getIdProduit())]);
                $saveRank = $saveRank + 1;
            }else{
                array_push($produitListFinal[$saveRank][1],$produitList[$i]);
            }
        }
        return $produitListFinal;
    }

    public function getDevis($em,$userId){
        $panier = [];
        $panier = $em->getRepository(Commande::class)->findById($userId);
        return $panier;
    }

    public function getDevisAdmin($em){
        $panier = [];
        $panier = $em->getRepository(Commande::class)->findByNothing();
        return $panier;
    }

}
