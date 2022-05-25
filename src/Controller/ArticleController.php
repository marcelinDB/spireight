<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use App\Entity\Gestionstock;
use App\Entity\Produitsphares;
use App\Entity\Liaison;
use App\Entity\Promotion;
use App\Entity\Utilisateur;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\PacksRepository;
use App\Repository\GestionStockRepository;
use App\Repository\ProduitsPharesRepository;
use App\Repository\PromotionPharesRepository;
use App\Repository\LiaisonRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Controller\MenuController;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;


class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article')]
    public function article(): Response
    {
        return $this->redirectToRoute('boutique');
    }
    #[Route('/article/{id}', name: 'article_content', methods: ['GET','POST'])]
    public function index(EntityManagerInterface $em,RequestStack $requestStack,ManagerRegistry $doctrine,Request $request,$id): Response
    {
        $menu = new MenuController($em);
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getCurrentRequest()->getSession();
        if(!$session->has('email')){
            $statusConnexion = true;
        }else{
            $statusConnexion = false;
        }
        $response_available = false;
        $articleInfo = [];
        if(isset($id)){
            $louer = $request->request->get('louer');
            $acheter = $request->request->get('acheter');
            if(isset($louer)){
                $this->setPanier($doctrine,$request->request->get('location'),$session,1);
                return $this->redirectToRoute('panier');
            }
            if(isset($acheter)){
                $this->setPanier($doctrine,$request->request->get('achat'),$session,0);
                return $this->redirectToRoute('panier');
            }
            $response_available = true;
            $articleInfo = $this->getArticle($em,$id);
            try {
                $similary = $this->getRandomProduct($em,$articleInfo);
            } catch (\Throwable $th) {
                return $this->redirectToRoute('boutique');
            }
            try {
                shuffle($similary);
            } catch (\Throwable $th) {}
            $promo = $this->getPromo($em,$id);
            $articleInfoGestion = [];
        }
        
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'response_available' => $response_available,
            'articleInfo' => $articleInfo,
            'promo' => $promo,
            'stock' => $articleInfoGestion,
            'similary' => $similary,
            'statusConnexion' => $statusConnexion,
            'menuJs' => $menu->getJson(),
            'today' => date("Y/m/d")
        ]);
    }

    public function getArticle($em,$id)
    {
        return $em->getRepository(Gestionstock::class)->findByProductInfo($id);
    }

    public function getPack($em)
    {
        return $em->getRepository(Liaison::class)->findById($_GET['pack']);
    }

    public function getPromo($em,$id)
    {
        return $em->getRepository(Promotion::class)->findByid($id);
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

    private function setPanier($doctrine,$id,$session,$jour):Response{
        $entityManager = $doctrine->getManager();
        $user = new Panier();
        $user->setIdUsers($entityManager->getRepository(Utilisateur::class)->find($session->get('id')));
        $user->setIdGestionStock($entityManager->getRepository(Gestionstock::class)->find($id));
        $user->setQuantite(1);
        $user->setJour($jour);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response($user->getId());
    }
}