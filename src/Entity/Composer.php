<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComposerRepository")
 */
class Composer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="composer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande", inversedBy="composer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * Composer constructor
     *
     * @param $quantite
     * @param $produit
     */
    public function __construct($quantite,$produit)
    {
        $this->quantite = $quantite;
        $this->produit = $produit;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
    //récupère le montant total du panier
    public function getSousTotal()
    {
        return $this->quantite * $this->getProduit()->getPrixProduit();
    }
    //Converti le type int en string
    public function __toString()
    {
        return $this->getProduit()->getNomProduit();
    }
}
