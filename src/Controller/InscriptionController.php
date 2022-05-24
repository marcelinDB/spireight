<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\MailerController;
use Symfony\Component\Mailer\MailerInterface;

use App\Controller\MenuController;

use Symfony\Component\HttpFoundation\RequestStack;


class InscriptionController extends AbstractController
{
    public $message = "";
    #[Route('/inscription', name: 'inscription')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,RequestStack $requestStack,MailerInterface $mailer): Response
    {
        $menu = new MenuController($em);
        $mailerC = new MailerController();
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            $statusConnexion = false;
        }
        $status = false;
        if(isset($_POST['email'])){
            if(count($em->getRepository(Utilisateur::class)->findByEmail($_POST['email'])) == 0){
                if($this->setUtilisateur($doctrine) == ""){
                    $this->message = "Votre compte n'a pas pu être créé.";
                }else{
                    echo "<script>window.location.replace('./connexion');</script>";
                    $mailerC->sendEmail($mailer, "Merci de votre inscription",$_POST['email'],"<h1>Bienvenue, Monsieur ".$_POST['prenom']." sur notre site ... .</h1>");
                }
            }else{
                $this->message =  "Vous avez déjà un compte.";
            }
        }
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
            'status' => $status,
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'message' => $this->message
        ]);
    }

    private function setUtilisateur($doctrine):Response{
        $entityManager = $doctrine->getManager();
        $user = new Utilisateur();
        $user->setNom($_POST['nom']);
        $user->setPrenom($_POST['prenom']);
        $user->setEmail($_POST['email']);
        $user->setTel($_POST['tel']);
        $user->setDateNais(new \DateTime(date('Y-m-d',strtotime($_POST['date']))));
        $user->setNomEntreprise($_POST['societe']);
        $user->setMdp(password_hash($_POST['mdp'], PASSWORD_DEFAULT));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }
}
