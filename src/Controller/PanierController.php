<?php

namespace App\Controller;

use App\Entity\Gestionstock;
use App\Entity\Panier;
use App\Entity\Promotion;
use App\Entity\DeletePanier;
use App\Entity\Commande;
use App\Entity\Status;
use App\Repository\GestionStockRepository;
use App\Repository\DeletePanierRepository;
use App\Repository\StatusRepository;
use App\Repository\PromotionRepository;
use App\Repository\panierRepository;
use App\Repository\CommmandeRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\MailerController;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\HttpFoundation\RequestStack;


class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,RequestStack $requestStack,MailerInterface $mailer): Response
    {
        $menu = new MenuController($em);
        $mailerC = new MailerController();
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
            echo "<script>window.location.replace('./connexion');</script>";
        }else{
            $statusConnexion = false;
        }
        if(isset($_POST['save'])){
            for ($i=0; $i < count($_POST['id']); $i++) {
                $this->setPanierUpdate($doctrine,$session,$_POST['id'][$i],$_POST['quantity'][$i],$_POST['type'][$i],$_POST['jour'][$i],$em);
            }
        }
        if(isset($_POST['trash'])){
            $this->deletePanier($doctrine,$_POST['trash']);
        }
        if(isset($_POST['envoie'])){
            for ($y=0; $y < count($_POST['id']); $y++) { 
                $this->setDevis($doctrine,$_POST['id'][$y],$session,$mailerC,$mailer);
                $this->setCommande($doctrine,$_POST['id'][$y],$_POST['date'][$y]);
            }
            //echo "<script>window.location.replace('./listdevis');</script>";
        }
        $articleInfo = $this->getArticle($em);
            try {
                $similary = $this->getRandomProduct($em,$articleInfo);
            } catch (\Throwable $th) {
               //echo "<script>window.location.replace('./boutique');</script>";
            }
            try {
                shuffle($similary);
            } catch (\Throwable $th) {
                //throw $th;
            }
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'similary' => $similary,
            'panier' => json_encode($this->getVente($this->getPanier($session->get('id'),$em),$em))
        ]);
    }

    public function getArticle($em)
    {
        return $em->getRepository(Gestionstock::class)->findByProductInfo(9);
    }

    public function getRandomProduct($em,$articleInfo)
    {
        $produitList = [];
        $produitListFinal = [];
        $produit = $em->getRepository(Gestionstock::class)->findProductByCategoryId($articleInfo[0]->getIdProduit()->getCat());
        foreach($produit as $roow){
            $gestionRetreive = $em->getRepository(Gestionstock::class)->findByProductDateWithProductId($roow->getIdProduit()->getId());
            if($gestionRetreive != []){
                array_push($produitList,[$gestionRetreive]);
            }
        }
        $save = "";
        $saveRank = -1;
        for($i=0; $i < count($produitList); $i++) { 
            if($save != $produitList[$i][0][0]->getIdProduit()){
                array_push($produitListFinal,[$produitList[$i][0],$em->getRepository(Promotion::class)-> findByid($produitList[$i][0][0]->getIdProduit())]);
                $saveRank = $saveRank + 1;
            }else{
                array_push($produitListFinal[$saveRank][1],$produitList[$i]);
            }
        }
        return $produitListFinal;
    }

    public function getPanier(int $id,$em): array
    {
        $noId = $em->getRepository(DeletePanier::class)->findAllIdPanier();
        $panierId = [];
        foreach ($noId as $value) {
            array_push($panierId, $value['id']);
        }
        $panier = [];
        $panier = $em->getRepository(Panier::class)->findByIdClient($id,$panierId);
        return $panier;
    }

    public function getVente($list,$em): array
    {
        for ($i=0; $i < count($list); $i++) {   
            $result = $em->getRepository(Gestionstock::class)->findByNomProduit($list[$i]['nom']);
            array_push($list[$i],[]);
            for ($y=0; $y < count($result); $y++) {
                array_push($list[0][0], [$result[$y]['nomType'],$result[$y]['prix'],$result[$y]['id'],$result[$y]['quantite']]);
            }
        }
        return $list;
    }

    private function setPanierUpdate($doctrine,$session,$id2,$quantite,$stock,$jour,$em):Response{
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Panier::class)->find($id2);
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $product->setIdGestionstock($em->getRepository(Gestionstock::class)->find($stock));
        $product->setQuantite($quantite*1);
        $product->setJour($jour*1);
        $entityManager->flush();
        return new Response($product->getId());
    }

    private function deletePanier($doctrine,$id){
        $qd = $doctrine->getManager()->getRepository(Panier::class)->createQueryBuilder('n');
        $qd->delete()
        ->where('n.id = :id')
        ->setParameter('id',$id);
        $result = $qd->getQuery()->getResult();
    }

    private function setDevis($doctrine,$id,$session,$mailerC,$mailer):Response{
        $entityManager = $doctrine->getManager();
        $panierId = $entityManager->getRepository(Panier::class)->find($id);
        $user = new DeletePanier();
        $user->setIdPanier($panierId);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        $mailerC->sendEmail($mailer, "Nouveau devis",'contact@pixel4d.fr',"<h1>Nouveau devis: ".$session->get('email')."</h1><a href='http://localhost:8000/listdevis' style='background-color:#23acce;border-radius:10px;padding:7px;cursor:pointer;font-size:1.5rem;'>Redirection liste des devis.</a>");
        return new Response($user->getId());
    }

    private function setCommande($doctrine,$id,$date):Response{
        $entityManager = $doctrine->getManager();
        if($date = new \DateTime(date('Y-m-d',strtotime("")))){
            $date = date("Y-m-d");
        }
        $user = new Commande();
        $user->setIdPanier($entityManager->getRepository(Panier::class)->find($id));
        $user->setDate(new \DateTime(date('Y-m-d',strtotime($date))));
        $user->setIdStatus($entityManager->getRepository(Status::class)->find(1));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }
}
