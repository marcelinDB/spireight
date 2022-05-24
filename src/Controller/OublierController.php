<?php

namespace App\Controller;

use App\Repository\HeaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Controller\MenuController;
use App\Controller\MailerController;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class OublierController extends AbstractController
{
    #[Route('/oublier', name: 'oublier')]
    public function index(EntityManagerInterface $em,RequestStack $requestStack,MailerInterface $mailer,ManagerRegistry $doctrine): Response
    {
        $menu = new MenuController($em);
        $mailerC = new MailerController();
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $statusConnexion = false;

        if(isset($_POST['email'])){
            $this->setMdp($doctrine,$session,$mailerC,$mailer);
        }

        return $this->render('oublier/index.html.twig', [
            'controller_name' => 'OublierController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson()
        ]);
    }

    private function setMdp($doctrine,$session,$mailerC,$mailer)
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->findByEmail($_POST['email'])[0];
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $newMdp = bin2hex(random_bytes(42));
        $product->setMdp(password_hash($newMdp, PASSWORD_DEFAULT));
        $entityManager->flush();
        $mailerC->sendEmail($mailer, "Modification de votre mot de passe",$_POST['email'],"<h1>Voici votre nouveau mot de passe:</h1><h2 style='color:red;'>".$newMdp."</h2>");
        echo "<script>window.location.replace('./connexion');</script>";
    }
}
