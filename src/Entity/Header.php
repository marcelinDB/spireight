<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Header
 *
 * @ORM\Table(name="header")
 * @ORM\Entity(repositoryClass="App\Repository\HeaderRepository")
 */
class Header
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
     * @var string
     *
     * @ORM\Column(name="vueHtml", type="text", length=65535, nullable=false)
     */
    private $vuehtml;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVuehtml(): ?string
    {
        return $this->vuehtml;
    }

    public function setVuehtml(string $vuehtml): self
    {
        $this->vuehtml = $vuehtml;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


}
