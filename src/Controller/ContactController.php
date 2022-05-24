<?php

namespace App\Controller;

use App\Entity\Header;
use App\Repository\HeaderRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MenuController;
use App\Controller\MailerController;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(EntityManagerInterface $em,RequestStack $requestStack,MailerInterface $mailer): Response
    {    
        $menu = new MenuController($em);
        $mailerC = new MailerController();
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $statusConnexion = false;
        if(isset($_POST['email'])){
            try {
                $mailerC->sendEmail($mailer, "Envoi d'un formulaire de contact",$_POST['email'],"<h1>Nous vous remercions de l'envoi de ce formulaire de contact</h1><p>Vous devriez recevoir une réponse dans les prochaines 48 heures.</p>");
                $mailerC->sendEmail($mailer, "Formulaire de contact","contact@pixel4d.fr","<h1>Forumlaire de contact à propos de: ".$_POST['sujet']."</h1><p>De la part de ".$_POST['email']."</p>"."<p>Tel: ".$_POST['tel']."</p>"."<p>".$_POST['message']."</p>");
            } catch (\Throwable $th) {
                print $th;
            }    
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson()
        ]);
    }
}
