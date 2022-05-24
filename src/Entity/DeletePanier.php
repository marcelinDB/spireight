<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeletePanier
 *
 * @ORM\Table(name="delete_panier", indexes={@ORM\Index(name="panier_delete", columns={"id_panier"})})
 * @ORM\Entity(repositoryClass="App\Repository\DeletePanierRepository")
 */
class DeletePanier
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
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id")
     * })
     */
    private $idPanier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): self
    {
        $this->idPanier = $idPanier;

        return $this;
    }


}
