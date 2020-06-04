<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroTable;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCommande;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reglement", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $reglement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Composer", mappedBy="commande", orphanRemoval=true)
     */
    private $composer;

    public function __construct()
    {
        $this->composer = new ArrayCollection();
    }

    /**
     * get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroTable(): ?int
    {
        return $this->numeroTable;
    }

    public function setNumeroTable(int $numeroTable): self
    {
        $this->numeroTable = $numeroTable;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReglement(): ?Reglement
    {
        return $this->reglement;
    }

    public function setReglement(?Reglement $reglement): self
    {
        $this->reglement = $reglement;

        return $this;
    }

    /**
     * @return Collection|Composer[]
     */
    public function getComposer(): Collection
    {
        return $this->composer;
    }

    public function addComposer(Composer $composer): self
    {
        if (!$this->composer->contains($composer)) {
            $this->composer[] = $composer;
            $composer->setCommande($this);
        }

        return $this;
    }

    public function removeComposer(Composer $composer): self
    {
        if ($this->composer->contains($composer)) {
            $this->composer->removeElement($composer);
            // set the owning side to null (unless already changed)
            if ($composer->getCommande() === $this) {
                $composer->setCommande(null);
            }
        }

        return $this;
    }
}
