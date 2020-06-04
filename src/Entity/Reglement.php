<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReglementRepository")
 */
class Reglement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

      /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateReglement;
   
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $modeReglement;

    public function getId(): ?int
    {
        return $this->id;
    }

 

    public function getModeReglement(): ?string
    {
        return $this->modeReglement;
    }

    public function setModeReglement(?string $modeReglement): self
    {
        $this->modeReglement = $modeReglement;

        return $this;
    }
    //permet de convertir la date
    public function __toString()
    {
        return (string) $this->dateReglement;
    }
    public function getDateReglement(): ?\DateTimeInterface
    {
        return $this->dateReglement;
    }

    public function setDateReglement(\DateTimeInterface $dateReglement): self
    {
        $this->dateReglement = $dateReglement;

        return $this;
    }



}
