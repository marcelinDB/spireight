<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Repository\panierRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;


class ProfilController extends AbstractController
{
    public $message = "";
    #[Route('/profil', name: 'profil')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,RequestStack $requestStack): Response
    {
        $id = 0;
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        echo "<script>var message = ''</script>";
        if(isset($_POST['deconnexion'])){
            $session->remove('email');
            $session->remove('id');
        }
        if(!$session->has('email')){
            $statusConnexion = true;
            echo "<script>window.location.replace('./connexion');</script>";
        }else{
            $id = $session->get('id');
            $statusConnexion = false;
        }
        if(isset($_POST['email'])){
            $this->setEmail($doctrine,$session);
        }
        if(isset($_POST['mdp'])){
            $this->setMdp($doctrine,$session);
        }
        if(isset($_POST['nom'])){
            $this->setNom($doctrine,$session);
        }
        if(isset($_POST['prenom'])){
            $this->setPrenom($doctrine,$session);
        }
        if(isset($_POST['societe'])){
            $this->setSociete($doctrine,$session);
        }
        if(isset($_POST['tel'])){
            $this->setTel($doctrine,$session);
        }
        if(isset($_POST['date'])){
            $this->setDate($doctrine,$session);
        }
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'PanierController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'account' => $em->getRepository(Utilisateur::class)->find($session->get('id')),
            'message' => $this->message,
            'id' => $id
        ]);
    }

    private function setEmail($doctrine,$session):Response{
        $entityManager = $doctrine->getManager();
        if(count($entityManager->getRepository(Utilisateur::class)->findByEmail($_POST['email'])) == 0){
            $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
            if (!$product) {
                throw $this->createNotFoundException(
                'No product found for id '.$id
            );
            }
            $product->setEmail($_POST['email']);
            $entityManager->flush();
            $session->set('email', $_POST['email']);
            return new Response($product->getId());
        }else{
            $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
            $this->message = "Il existe un compte avec cette email.";
            return new Response($product->getId());
        }
        return "";
    }

    private function setNom($doctrine,$session):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setNom($_POST['nom']);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setPrenom($doctrine,$session):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setPrenom($_POST['prenom']);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setTel($doctrine,$session):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setTel($_POST['tel']);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setSociete($doctrine,$session):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setNomEntreprise($_POST['societe']);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setDate($doctrine,$session):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setDateNais(new \DateTime(date('Y-m-d',strtotime($_POST['date']))));
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setMdp($doctrine,$session):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setMdp(password_hash($_POST['mdp'], PASSWORD_DEFAULT));
        $entityManager->flush();
        return new Response($product->getId());
    }
}
