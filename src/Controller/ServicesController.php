<?php

namespace App\Controller;

use App\Entity\Gestionstock;
use App\Entity\Panier;
use App\Entity\DeletePanier;
use App\Entity\Commande;
use App\Entity\Status;
use App\Entity\Promotion;
use App\Entity\Produit;
use App\Repository\GestionStockRepository;
use App\Repository\DeletePanierRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StatusRepository;
use App\Repository\PromotionRepository;
use App\Repository\panierRepository;
use App\Repository\CommmandeRepository;

use App\Controller\MenuController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class ServicesController extends AbstractController
{
    private $requestStack;

    public function __construct(
        RequestStack $requestStack
    ){
        $this->requestStack = $requestStack;
    }

    #[Route('/listegestion', name: 'liste_gestion')]
    public function listeGestion(EntityManagerInterface $em,ManagerRegistry $doctrine): Response
    {
        $menu = new MenuController($em);
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
            return $this->redirectToRoute('connexion');
        }else{
            if($session->get('id') != 11 && $session->get('id') != 13){
                return $this->redirectToRoute('profil');
            }
            $statusConnexion = false;
        }
        return $this->render('services/liste_gestion.html.twig', [
            'controller_name' => 'ListeGestionController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'product' => json_encode($em->getRepository(Produit::class)->findByName())
        ]);
    }

    #[Route('/devis', name: 'devis')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine): Response
    {
        $menu = new MenuController($em);
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
            return $this->redirectToRoute('connexion'); 
        }else{
            $statusConnexion = false;
        }

        if(!isset($_GET['dateref']) && validateDate($_GET['dateref'])){
            return $this->redirectToRoute('list_devis');
        }

        //Ajout Admin
        if($session->get('id') != 11 && $session->get('id') != 13){
            $devis =  $this->getDevis($em,$session->get('id'),$_GET['dateref']);
        }else{
            $devis = $this->getDevis($em,$_GET['compte'],$_GET['dateref']);
        }
        return $this->render('services/facture.html.twig', [
            'controller_name' => 'FactureController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'devis' => $devis
        ]);
    }

    #[Route('/listdevis', name: 'list_devis')]
    public function listDevis(EntityManagerInterface $em,ManagerRegistry $doctrine): Response
    {
        $statusAdmin = 0;
        $menu = new MenuController($em);
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
            return $this->redirectToRoute('connexion');
        }else{
            $statusConnexion = false;
        }
        $articleInfo = $this->getArticle($em);
            try {
                $similary = $this->getRandomProduct($em,$articleInfo);
            } catch (\Throwable $th) {
               return $this->redirectToRoute('boutique');
            }
            try {
                shuffle($similary);
            } catch (\Throwable $th) {
                //throw $th;
            }
            //Ajout Admin id
            if($session->get('id') != 11 && $session->get('id') != 13){
                $devis = $this->getDevisList($em,$session->get('id'));
            }else{
                $statusAdmin = 1;
                $devis = $this->getDevisAdminList($em);
            }
        return $this->render('services/list_devis.html.twig', [
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

    public function getDevisList($em,$userId){
        $panier = [];
        $panier = $em->getRepository(Commande::class)->findById($userId);
        return $panier;
    }

    public function getDevisAdminList($em){
        $panier = [];
        $panier = $em->getRepository(Commande::class)->findByNothing();
        return $panier;
    }

    public function getDevis($em,$userId,$date){
        $panier = [];
        $panier = $em->getRepository(Commande::class)->findByDate(new \DateTime(date('Y-m-d',strtotime($date))),$userId);
        return $panier;
    }

    public function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
