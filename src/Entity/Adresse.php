<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $rue = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(type: 'integer', nullable: true)] // Modification du type de colonne en 'integer'
    private ?int $codePostal = null; // Modification du type de propriété en 'int'

    #[ORM\ManyToOne(targetEntity: Employe::class)]
#[ORM\JoinColumn(name: "employe_id", referencedColumnName: "id")]
private ?Employe $employe = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getCodePostal(): ?int // Modification du type de retour en 'int'
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): self // Modification du type de paramètre en 'int'
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;
        return $this;
    }

    public function __toString(): string
    {
        return $this->rue . ' ' . $this->ville . ' ' . $this->codePostal;
    }
}
