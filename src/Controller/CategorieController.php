<?php

namespace App\Controller;

use App\Entity\CategorieSecondaire;
use App\Repository\CategorieSecondaireRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\MenuController;

use Symfony\Component\HttpFoundation\RequestStack;


class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'categorie')]
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
        $response_available = false;
        if(isset($_GET['categorie'])){
            $categorie = $this->getCategorie($em);
            $response_available = true;
        }else{
            echo "<script>window.location.replace('./');</script>";
        }
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'response_available' => $response_available,
            'categorie' => $categorie,
            'categorieName' => $categorie[0]->getCat()->getNom(),
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson()
        ]);
    }

    public function getCategorie($em):array
    {
        return $em->getRepository(CategorieSecondaire::class)->findByIdPrimaryCategorie($_GET['categorie']);
    }
}
