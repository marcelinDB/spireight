<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Liaisoncategorie;
use App\Entity\Produit;
use App\Entity\Produitsphares;
use App\Entity\Promotion;
use App\Entity\Gestionstock;
use App\Entity\Type;
use App\Entity\CategorieSecondaire;
use App\Repository\UtilisateurRepository;
use App\Repository\LiaisonCategorieRepository;
use App\Repository\ProduitspharesRepository;
use App\Repository\GestionStockRepository;
use App\Repository\PromotionRepository;
use App\Repository\CategorieSecondaireRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class GestionProduitController extends AbstractController
{
    public $key;
    #[Route('/gestion_produit', name: 'gestion_produit')]
    public function index(EntityManagerInterface $em,ManagerRegistry $doctrine,RequestStack $requestStack): Response
    {
        
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            if($session->get('id') != 11 && $session->get('id') != 13){
                return $this->redirectToRoute('profil');
            }
            $statusConnexion = false;
        }
        if(!$session->has('email')){
            return $this->redirectToRoute('connexion');
        }

        if(isset($_GET['idproduct'])){
            $produitProduct = $_GET['idproduct'];
            $produitInfo = $em ->getRepository(Produit::class)->find($_GET['idproduct']);
            $produitPromo = $em ->getRepository(Promotion::class)->findByidProduct($_GET['idproduct']);
            $produitPhare = $em ->getRepository(Produitsphares::class)->findByidProduct($_GET['idproduct']);
            $produitAchat = $em ->getRepository(Gestionstock::class)->findByidProduct($_GET['idproduct'],2);
            $produitOccassion = $em ->getRepository(Gestionstock::class)->findByidProduct($_GET['idproduct'],3);
            $produitLocation = $em ->getRepository(Gestionstock::class)->findByidProduct($_GET['idproduct'],1);
        }else{
            $produitProduct = "";
            $produitInfo = [];
            $produitPromo = [];
            $produitPhare = [];
            $produitAchat = [];
            $produitOccassion = [];
            $produitLocation = [];
        }

        if(isset($_POST['nom'])){   
            if(!isset($_GET['idproduct'])){
                $this->setProduit($doctrine);
                if(isset($_POST['promo'])){
                    $promo = $_POST['promo'];
                }else{
                    $promo = 0.0;
                }
                if(isset($_POST['datepromo'])){
                    $this->setPromo($doctrine,$this->key,$promo,$_POST['datepromo']);
                }else{
                    $this->setPromo($doctrine,$this->key,$promo,date("Y-m-d"));
                }

                if(isset($_POST['datephare'])){
                    $this->setPhare($doctrine,$this->key,$_POST['datepromo']);
                }else{
                    $this->setPhare($doctrine,$this->key,date("Y-m-d"));
                }

                if(isset($_POST['achatqt'])){
                    $this->setStock($doctrine,$this->key,2,$_POST['achatqt'],$_POST['achatprix'],0);
                }

                if(isset($_POST['occassionqt'])){
                    $this->setStock($doctrine,$this->key,3,$_POST['occassionqt'],$_POST['occassionprix'],0);
                }

                if(isset($_POST['locationqt'])){
                    $this->setStock($doctrine,$this->key,1,$_POST['locationqt'],$_POST['locationprix'],$_POST['locationcaution']);
                }
                return $this->redirectToRoute('gestion_produit',['idproduct'=>$this->key]);
            }else{
                $this->setUpdateProduit($doctrine,$_GET['idproduct']);
                $this->setUpdatePromo($doctrine,$_GET['idproduct'],$_POST['promo'],$_POST['datepromo']);
                $this->setUpdatePhare($doctrine,$_GET['idproduct'],$_POST['datephare']);
                if(isset($_POST['achatqt'])){
                    $this->setUpdateStock($doctrine,$_GET['idproduct'],2,$_POST['achatqt'],$_POST['achatprix'],0);
                }

                if(isset($_POST['occassionqt'])){
                    $this->setUpdateStock($doctrine,$_GET['idproduct'],3,$_POST['occassionqt'],$_POST['occassionprix'],0);
                }

                if(isset($_POST['locationqt'])){
                    $this->setUpdateStock($doctrine,$_GET['idproduct'],1,$_POST['locationqt'],$_POST['locationprix'],$_POST['locationcaution']);
                }
            }
            
        }

        return $this->render('gestion_produit/index.html.twig', [
            'controller_name' => 'GestionProduitController',
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'categorie' => $this->getAllCategorie($em),
            'produitInfo' => $produitInfo ,
            'produitPromo' => $produitPromo,
            'produitPhare' => $produitPhare,
            'produitAchat' => $produitAchat,
            'idproduct' => $produitProduct,
            'produitOccassion' => $produitOccassion,
            'produitLocation' => $produitLocation
        ]);
    }



    private function setProduit($doctrine):Response{
        $entityManager = $doctrine->getManager();
        $cat = $entityManager->getRepository(CategorieSecondaire::class)->find($_POST['categorie']);
        $user = new Produit();
        $user->setNom($_POST['nom']);
        $user->setMarque($_POST['marque']);
        $user->setDescription($_POST['description']);
        $user->setImg($_POST['img']);
        $user->setImg2($_POST['img1']);
        $user->setImg3($_POST['img2']);
        $user->setImg4($_POST['img3']);
        $user->setImg5($_POST['img4']);
        $user->setDate(new \DateTime(date('Y-m-d',strtotime(date("Y-m-d")))));
        $user->setCat($cat);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        $this->key = $user->getId();
        return new Response($user->getId());
    }

    public function getAllCategorie($em)
    {
        //dd($em->getRepository(Liaisoncategorie::class)->findBySecTerc());
        return $em->getRepository(Liaisoncategorie::class)->findBySecTerc();
    }

    private function setPromo($doctrine,$id,$promo,$date):Response{
        $entityManager = $doctrine->getManager();
        $cat = $entityManager->getRepository(Produit::class)->find($id);
        $user = new Promotion();
        $user->setIdProduit($cat);
        $user->setTaux(floatval($promo));
        $user->setDateFin(new \DateTime(date('Y-m-d',strtotime($date))));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }

    private function setPhare($doctrine,$id,$date):Response{
        $entityManager = $doctrine->getManager();
        $cat = $entityManager->getRepository(Produit::class)->find($id);
        $user = new Produitsphares();
        $user->setIdProduit($cat);
        $user->setDate(new \DateTime(date('Y-m-d',strtotime($date))));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }

    private function setStock($doctrine,$id,$idType,$quantite,$prix,$caution):Response{
        $entityManager = $doctrine->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $type = $entityManager->getRepository(Type::class)->find($idType);
        $user = new Gestionstock();
        $user->setIdProduit($produit);
        $user->setIdType($type);
        $user->setQuantite(intval($quantite));
        $user->setPrix(floatval($prix));
        $user->setCaution(intval($caution));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }

//5555555
    private function setUpdateProduit($doctrine,$id):Response{
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Produit::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $cat = $entityManager->getRepository(CategorieSecondaire::class)->find($_POST['categorie']);
        $user->setNom($_POST['nom']);
        $user->setMarque($_POST['marque']);
        $user->setDescription($_POST['description']);
        $user->setImg($_POST['img']);
        $user->setImg2($_POST['img1']);
        $user->setImg3($_POST['img2']);
        $user->setImg4($_POST['img3']);
        $user->setImg5($_POST['img4']);
        $user->setDate(new \DateTime(date('Y-m-d',strtotime(date("Y-m-d")))));
        $user->setCat($cat);
        $entityManager->flush();
        return new Response($user->getId());
    }
//5555555
    private function setUpdatePromo($doctrine,$id,$promo,$date):Response{
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Promotion::class)->findByidProduct($id);
        $user = $user[0];
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $cat = $entityManager->getRepository(Produit::class)->find($id);
        $user->setIdProduit($cat);
        $user->setTaux(floatval($promo));
        $user->setDateFin(new \DateTime(date('Y-m-d',strtotime($date))));
        $entityManager->flush();
        return new Response($user->getId());
    }
//5555555
    private function setUpdatePhare($doctrine,$id,$date):Response{
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Produitsphares::class)->findByidProduct($id);
        $user = $user[0];
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $cat = $entityManager->getRepository(Produit::class)->find($id);
        $user->setIdProduit($cat);
        $user->setDate(new \DateTime(date('Y-m-d',strtotime($date))));
        $entityManager->flush();
        return new Response($user->getId());
    }
//5555555
    private function setUpdateStock($doctrine,$id,$idType,$quantite,$prix,$caution):Response{
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(Gestionstock::class)->findByidProduct($id,$idType);
        $user = $user[0];
        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $type = $entityManager->getRepository(Type::class)->find($idType);
        $user->setIdProduit($produit);
        $user->setIdType($type);
        $user->setQuantite(intval($quantite));
        $user->setPrix(floatval($prix));
        $user->setCaution(intval($caution));
        $entityManager->flush();
        return new Response($user->getId());
    }
}
