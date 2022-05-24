<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionsController extends AbstractController
{
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('conditions/cgu.html.twig', [
            'controller_name' => 'CguController',
        ]);
    }

    #[Route('/cgv', name: 'cgv')]
    public function cgv(): Response
    {
        return $this->render('conditions/cgv.html.twig', [
            'controller_name' => 'CgvController',
        ]);
    }
}
