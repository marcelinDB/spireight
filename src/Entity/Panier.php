<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier", indexes={@ORM\Index(name="user", columns={"id_users"}), @ORM\Index(name="gestion", columns={"id_gestionstock"})})
 * @ORM\Entity(repositoryClass="App\Repository\panierRepository")
 */
class Panier
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
     * @var int
     *
     * @ORM\Column(name="jour", type="integer", nullable=false)
     */
    private $jour;

    /**
     * @var \Gestionstock
     *
     * @ORM\ManyToOne(targetEntity="Gestionstock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_gestionstock", referencedColumnName="id")
     * })
     */
    private $idGestionstock;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_users", referencedColumnName="id")
     * })
     */
    private $idUsers;

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

    public function getJour(): ?int
    {
        return $this->jour;
    }

    public function setJour(int $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getIdGestionstock(): ?Gestionstock
    {
        return $this->idGestionstock;
    }

    public function setIdGestionstock(?Gestionstock $idGestionstock): self
    {
        $this->idGestionstock = $idGestionstock;

        return $this;
    }

    public function getIdUsers(): ?Utilisateur
    {
        return $this->idUsers;
    }

    public function setIdUsers(?Utilisateur $idUsers): self
    {
        $this->idUsers = $idUsers;

        return $this;
    }


}
