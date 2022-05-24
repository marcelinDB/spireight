<?php

namespace App\Controller;


use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\MenuController;

use Symfony\Component\HttpFoundation\RequestStack;

class ConnexionController extends AbstractController
{
    private $requestStack;
     
    #[Route('/connexion', name: 'connexion')]
    public function index(EntityManagerInterface $em,RequestStack $requestStack): Response
    {
        $message = "";
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            $statusConnexion = false;
        }
        if(!$session->has('email')){
            if(isset($_POST['email'])){
                $message = $this->getAccount($em);
            }
        }else{
            echo "<script>window.location.replace('./profil');</script>";
        }
        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'message' => $message
        ]);
    }
    private function getAccount($em){
        $account = [];
        $message = "";
        $account = $em->getRepository(Utilisateur::class)->findByEmail($_POST['email']);
        if(count($account) != 0){
            if(password_verify($_POST["mdp"], $account[0]->getMdp())){
                $this->setSession($account);
            }else{
                return $message = "Le mot de passe n'est pas valide.";
            }
        }else{
            return $message = "Le compte n'existe pas.";
        }
        return $message;
    }

    private function setSession($account){
        $session = $this->requestStack->getSession();

        // stores an attribute in the session for later reuse
        $session->set('id', $account[0]->getId());
        $session->set('email', $account[0]->getEmail());
        //$session->remove('foo');
        // gets an attribute by name
        //$foo = $session->get('foo');
        //
        echo "<script>window.location.replace('./');</script>";
    }


}
