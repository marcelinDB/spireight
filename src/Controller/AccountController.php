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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AccountController extends AbstractController
{
    private $requestStack;
    private $message = "";

    public function __construct(
        RequestStack $requestStack
    ){
        $this->requestStack = $requestStack;
    }

    #[Route('/deconnexion', name: 'logout', methods: ['GET','POST'])]
    public function logout(): Response
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $session->remove('email');
        $session->remove('id');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/connexion', name: 'connexion', methods: ['GET','POST'])]
    public function index(EntityManagerInterface $em,Request $request): Response
    {
        $message = "";
        $menu = new MenuController($em);
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            $statusConnexion = false;
        }
        if(!$session->has('email')){
            $email = $request->request->get('email');
            $mdp = $request->request->get('mdp');
            if(isset($email)){
                $message = $this->getAccount($em,$email,$mdp);
                if($message == ""){
                    return $this->redirectToRoute('app_home');
                }
            }
        }else{
            return $this->redirectToRoute('profil');
        }
        return $this->render('account/connexion.html.twig', [
            'controller_name' => 'ConnexionController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'message' => $message
        ]);
    }

    #[Route('/inscription', name: 'inscription', methods: ['GET','POST'])]
    public function inscription(EntityManagerInterface $em,ManagerRegistry $doctrine,MailerInterface $mailer,Request $request): Response
    {
        $menu = new MenuController($em);
        $mailerC = new MailerController();
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            $statusConnexion = false;
        }
        $status = false;
        $email = $request->request->get('email');
        if(isset($email)){
            if(count($em->getRepository(Utilisateur::class)->findByEmail($email)) == 0){
                if($this->setUtilisateur($doctrine,$email,$request->request->get('tel'),$request->request->get('mdp'),$request->request->get('nom'),$request->request->get('prenom'),$request->request->get('societe'),$request->request->get('date')) == ""){
                    $this->message = "Votre compte n'a pas pu être créé.";
                }else{
                    return $this->redirectToRoute('connexion');
                    $mailerC->sendEmail($mailer, "Merci de votre inscription",$email,"<h1>Bienvenue, Monsieur ".$request->request->get('prenom')." sur notre site ... .</h1>");
                }
            }else{
                $this->message =  "Vous avez déjà un compte.";
            }
        }
        return $this->render('account/inscription.html.twig', [
            'controller_name' => 'InscriptionController',
            'status' => $status,
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'message' => $this->message
        ]);
    }

    #[Route('/oublier', name: 'oublier', methods: ['GET','POST'])]
    public function oublier(EntityManagerInterface $em,MailerInterface $mailer,ManagerRegistry $doctrine,Request $request): Response
    {
        $menu = new MenuController($em);
        $mailerC = new MailerController();
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $statusConnexion = false;

        $email = $request->request->get('email');
        if(isset($email)){
            $this->setMdp($doctrine,$session,$mailerC,$mailer,$email);
        }

        return $this->render('account/oublier.html.twig', [
            'controller_name' => 'OublierController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson()
        ]);
    }

    #[Route('/profil', name: 'profil')]
    public function profil(EntityManagerInterface $em,ManagerRegistry $doctrine,Request $request): Response
    {
        $id = 0;
        $menu = new MenuController($em);
        $session = $this->requestStack->getCurrentRequest()->getSession();
        echo "<script>var message = ''</script>";
        if(!$session->has('email')){
            $statusConnexion = true;
            return $this->redirectToRoute('connexion');
        }else{
            $id = $session->get('id');
            $statusConnexion = false;
        }
        $email = $request->request->get('email');$mdp = $request->request->get('mdp');$nom = $request->request->get('nom');$prenom = $request->request->get('prenom');$societe = $request->request->get('societe');$tel = $request->request->get('tel');$date = $request->request->get('date');
        if(isset($email)){
            $this->setEmailProfil($doctrine,$session,$email);
        }
        if(isset($mdp)){
            $this->setMdpProfil($doctrine,$session,$mdp);
        }
        if(isset($nom)){
            $this->setNomProfil($doctrine,$session,$nom);
        }
        if(isset($prenom)){
            $this->setPrenomProfil($doctrine,$session,$prenom);
        }
        if(isset($societe)){
            $this->setSocieteProfil($doctrine,$session,$societe);
        }
        if(isset($tel)){
            $this->setTelProfil($doctrine,$session,$tel);
        }
        if(isset($date)){
            $this->setDateProfil($doctrine,$session,$date);
        }
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'PanierController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'account' => $em->getRepository(Utilisateur::class)->find($session->get('id')),
            'message' => $this->message,
            'id' => $id
        ]);
    }

    private function setMdp($doctrine,$session,$mailerC,$mailer,$email)
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->findByEmail($email)[0];
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $newMdp = bin2hex(random_bytes(42));
        $product->setMdp(password_hash($newMdp, PASSWORD_DEFAULT));
        $entityManager->flush();
        $mailerC->sendEmail($mailer, "Modification de votre mot de passe",$email,"<h1>Voici votre nouveau mot de passe:</h1><h2 style='color:red;'>".$newMdp."</h2>");
        return $this->redirectToRoute('connexion');
    }

    private function setUtilisateur($doctrine,$email,$tel,$mdp,$nom,$prenom,$societe,$date):Response{
        $entityManager = $doctrine->getManager();
        $user = new Utilisateur();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setTel($tel);
        $user->setDateNais(new \DateTime(date('Y-m-d',strtotime($date))));
        $user->setNomEntreprise($societe);
        $user->setMdp(password_hash($mdp, PASSWORD_DEFAULT));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }

    private function getAccount($em,$email,$mdp)
    {
        $account = [];
        $message = "";
        $account = $em->getRepository(Utilisateur::class)->findByEmail($email);
        if(count($account) != 0){
            if(password_verify($mdp, $account[0]->getMdp())){
                $this->setSession($account);
            }else{
                return $message = "Le mot de passe n'est pas valide.";
            }
        }else{
            return $message = "Le compte n'existe pas.";
        }
        return $message;
    }

    private function setSession($account)
    {
        $session = $this->requestStack->getSession();
        $session->set('id', $account[0]->getId());
        $session->set('email', $account[0]->getEmail());
    }

    private function setEmailProfil($doctrine,$session,$email):Response{
        $entityManager = $doctrine->getManager();
        if(count($entityManager->getRepository(Utilisateur::class)->findByEmail($email)) == 0){
            $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
            if (!$product) {
                throw $this->createNotFoundException(
                'No product found for id '.$id
            );
            }
            $product->setEmail($email);
            $entityManager->flush();
            $session->set('email', $email);
            return new Response($product->getId());
        }else{
            $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
            $this->message = "Il existe un compte avec cette email.";
            return new Response($product->getId());
        }
        return "";
    }

    private function setNomProfil($doctrine,$session,$nom):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setNom($nom);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setPrenomProfil($doctrine,$session,$prenom):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setPrenom($prenom);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setTelProfil($doctrine,$session,$tel):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setTel($tel);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setSocieteProfil($doctrine,$session,$societe):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setNomEntreprise($societe);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setDateProfil($doctrine,$session,$date):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setDateNais(new \DateTime(date('Y-m-d',strtotime($date))));
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function setMdpProfil($doctrine,$session,$mdp):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Utilisateur::class)->find($session->get('id'));
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setMdp(password_hash($mdp, PASSWORD_DEFAULT));
        $entityManager->flush();
        return new Response($product->getId());
    }
}
