<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use App\Entity\Gestionstock;
use App\Entity\Produitsphares;
use App\Entity\Liaison;
use App\Entity\Promotion;
use App\Entity\Utilisateur;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\PacksRepository;
use App\Repository\GestionStockRepository;
use App\Repository\ProduitsPharesRepository;
use App\Repository\PromotionPharesRepository;
use App\Repository\LiaisonRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;


class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article')]
    public function index(EntityManagerInterface $em,RequestStack $requestStack,ManagerRegistry $doctrine): Response
    {
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            $statusConnexion = false;
        }
        $response_available = false;
        $articleInfo = [];
        if(isset($_GET['id'])){
                
            if(isset($_POST['louer'])){
                $this->setPanier($doctrine,$_POST['location'],$session,1);
                echo "<script>window.location.replace('./panier');</script>";
            }
            if(isset($_POST['acheter'])){
                $this->setPanier($doctrine,$_POST['achat'],$session,0);
                echo "<script>window.location.replace('./panier');</script>";
            }
            $response_available = true;
            $articleInfo = $this->getArticle($em);
            try {
                $similary = $this->getRandomProduct($em,$articleInfo);
            } catch (\Throwable $th) {
                echo "<script>window.location.replace('./boutique');</script>";
            }
            try {
                shuffle($similary);
            } catch (\Throwable $th) {
                //throw $th;
            }
            $promo = $this->getPromo($em);
            $articleInfoGestion = [];
        }
        
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'response_available' => $response_available,
            'articleInfo' => $articleInfo,
            'promo' => $promo,
            'stock' => $articleInfoGestion,
            'similary' => $similary,
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'today' => date("Y/m/d")
        ]);
    }

    public function getArticle($em)
    {
        return $em->getRepository(Gestionstock::class)->findByProductInfo($_GET['id']);
    }

    public function getPack($em)
    {
        return $em->getRepository(Liaison::class)->findById($_GET['pack']);
    }

    public function getPromo($em)
    {
        return $em->getRepository(Promotion::class)->findByid($_GET['id']);
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

    private function setPanier($doctrine,$id,$session,$jour):Response{
        $entityManager = $doctrine->getManager();
        $user = new Panier();
        $user->setIdUsers($entityManager->getRepository(Utilisateur::class)->find($session->get('id')));
        $user->setIdGestionStock($entityManager->getRepository(Gestionstock::class)->find($id));
        $user->setQuantite(1);
        $user->setJour($jour);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }
}