<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Produit;
use App\Repository\ProduitRepository;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class ListeGestionController extends AbstractController
{
    #[Route('/listegestion', name: 'liste_gestion')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,RequestStack $requestStack): Response
    {
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
            echo "<script>window.location.replace('./connexion');</script>";
        }else{
            if($session->get('id') != 11 && $session->get('id') != 13){
                echo "<script>window.location.replace('./profil');</script>";
            }
            $statusConnexion = false;
        }
        return $this->render('liste_gestion/index.html.twig', [
            'controller_name' => 'ListeGestionController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'product' => json_encode($em->getRepository(Produit::class)->findByName())
        ]);
    }
}
