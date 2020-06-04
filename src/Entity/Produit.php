<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 * @Vich\Uploadable
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
    
     */
    private $nomProduit;

    

    /**
     *
     *
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="imageProduit")
     *
     * @var File|null
     */
    private $imageFile;


    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $prixProduit;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptProduit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Composer", mappedBy="produit", orphanRemoval=true)
     */
    private $composer;

    /**
         * @ORM\Column(type="string", length=150)
         */
        private $imageProduit;



    public function __construct()
    {
        $this->composer = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }


    public function getPrixProduit(): ?string
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(string $prixProduit): self
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    public function getDescriptProduit(): ?string
    {
        return $this->descriptProduit;
    }

    public function setDescriptProduit(string $descriptProduit): self
    {
        $this->descriptProduit = $descriptProduit;

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
            $composer->setProduit($this);
        }

        return $this;
    }

    public function removeComposer(Composer $composer): self
    {
        if ($this->composer->contains($composer)) {
            $this->composer->removeElement($composer);
            // set the owning side to null (unless already changed)
            if ($composer->getProduit() === $this) {
                $composer->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * Get nOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @return  File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set nOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @param  File|null  $imageFile   NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @throws Exception
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {

            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setImageProduit(?string $imageProduit): void
    {
        $this->imageProduit = $imageProduit;
    }

    public function getImageProduit(): ?string
    {
        return $this->imageProduit;
    }
}