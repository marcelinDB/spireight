<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Liaison
 *
 * @ORM\Table(name="liaison", indexes={@ORM\Index(name="liaison_pack", columns={"idpack"}), @ORM\Index(name="liaison_produit", columns={"id_produit"})})
 * @ORM\Entity(repositoryClass="App\Repository\LiaisonRepository")
 */
class Liaison
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
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Packs
     *
     * @ORM\ManyToOne(targetEntity="Packs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idpack", referencedColumnName="id")
     * })
     */
    private $idpack;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id")
     * })
     */
    private $idProduit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdpack(): ?Packs
    {
        return $this->idpack;
    }

    public function setIdpack(?Packs $idpack): self
    {
        $this->idpack = $idpack;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }


}
