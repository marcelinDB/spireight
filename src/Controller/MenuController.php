<?php

namespace App\Controller;

use App\Entity\Liaisoncategorie;
use App\Entity\CategorieSecondaire;
use App\Repository\LiaisonCategorieRepository;
use App\Repository\CategorieSecondaireRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController
{
    private $em;
    private $JSON;
    
    public function __construct($em)
    {
        $this->em = $em;
        //dd($this->getTable($this->getCategorie()));
        $this->JSON = json_encode($this->getTable($this->getCategorie()));
    }

    public function test(): string
    {
        return "ok";
    }

    public function getCategorie():array
    {
        return $this->em->getRepository(Liaisoncategorie::class)->findByAllOne();
    }

    public function getJson():string
    {
        return $this->JSON;
    }

    public function getTable($table):array
    {
        $result = [];
        $resultConverter = [];
        $rank = -1;
        $lastCat = '1600000000';
        for ($i=0; $i < count($table); $i++) { 
            if($table[$i]['id'] != $lastCat){
                $rank = $rank + 1;
                array_push($result,[[],[]]);
                $lastCat = $table[$i]['id'];
                array_push($result[$rank][0],$table[$i]['cattrois']);
                array_push($result[$rank][1],$table[$i]['catdeux']);
            }else{
                array_push($result[$rank][0],$table[$i]['cattrois']);
                array_push($result[$rank][1],$table[$i]['catdeux']);
            }
        }
        for ($y=0; $y < count($result); $y++) { 
            array_push($resultConverter,[array_values(array_unique($result[$y][0])),[]]);
        }
        
        for ($z=0; $z < count($resultConverter); $z++) { 
            for ($h=0; $h < count($resultConverter[$z][0]); $h++) { 
                array_push($resultConverter[$z][1],[]);
                for ($k=0; $k < count($result[$z][0]); $k++) {
                    if($result[$z][0][$k] == $resultConverter[$z][0][$h]){
                        array_push($resultConverter[$z][1][$h],$result[$z][1][$k]);
                    }
                }
            }
        }
    
        return($resultConverter);
    }

}
