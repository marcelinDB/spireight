<?php

namespace App\Controller;

use App\Entity\Liaison;
use App\Repository\LiaisonRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\MenuController;

use Symfony\Component\HttpFoundation\RequestStack;


class PacksController extends AbstractController
{
    #[Route('/packs', name: 'packs')]
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
        $response_available = true;
        $packs = $em->getRepository(Liaison::class)->findByOrderToDate();
        return $this->render('packs/index.html.twig', [
            'controller_name' => 'CategorieController',
            'packs' => $packs,
            'response_available' => $response_available,
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson()
        ]);
    }
}
