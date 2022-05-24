<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Gestionstock;
use App\Entity\Panier;
use App\Entity\DeletePanier;
use App\Entity\Commande;
use App\Entity\Status;
use App\Repository\GestionStockRepository;
use App\Repository\DeletePanierRepository;
use App\Repository\StatusRepository;
use App\Repository\panierRepository;
use App\Repository\CommmandeRepository;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class FactureController extends AbstractController
{
    #[Route('/devis', name: 'devis')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,RequestStack $requestStack): Response
    {
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
        if(!isset($_GET['dateref']) && validateDate($_GET['dateref'])){
            echo "<script>window.location.replace('./listdevis');</script>";
        }
        //Ajout Admin
        if($session->get('id') != 11 && $session->get('id') != 13){
            $devis =  $this->getDevis($em,$session->get('id'),$_GET['dateref']);
        }else{
            $devis = $this->getDevis($em,$_GET['compte'],$_GET['dateref']);
        }
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'devis' => $devis
        ]);
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
