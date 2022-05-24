<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Liaisoncategorie
 *
 * @ORM\Table(name="liaisoncategorie", indexes={@ORM\Index(name="categorie_secondaire_Liaison", columns={"id_categorie_secondaire"}), @ORM\Index(name="categorie_terciaire_Liaison", columns={"id_categorie_tercaire"})})
 * @ORM\Entity(repositoryClass="App\Repository\LiaisonCategorieRepository")
 */
class Liaisoncategorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \CategorieSecondaire
     *
     * @ORM\ManyToOne(targetEntity="CategorieSecondaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_secondaire", referencedColumnName="id")
     * })
     */
    private $idCategorieSecondaire;

    /**
     * @var \CategorieTertiaire
     *
     * @ORM\ManyToOne(targetEntity="CategorieTertiaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_tercaire", referencedColumnName="id")
     * })
     */
    private $idCategorieTercaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCategorieSecondaire(): ?CategorieSecondaire
    {
        return $this->idCategorieSecondaire;
    }

    public function setIdCategorieSecondaire(?CategorieSecondaire $idCategorieSecondaire): self
    {
        $this->idCategorieSecondaire = $idCategorieSecondaire;

        return $this;
    }

    public function getIdCategorieTercaire(): ?CategorieTertiaire
    {
        return $this->idCategorieTercaire;
    }

    public function setIdCategorieTercaire(?CategorieTertiaire $idCategorieTercaire): self
    {
        $this->idCategorieTercaire = $idCategorieTercaire;

        return $this;
    }


}
