<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $prenom = null;

    // La propriété $adresse est une référence à l'entité Adresse, pas une chaîne.
    #[ORM\OneToOne(targetEntity: Adresse::class, cascade: ['persist', 'remove'])]
    private ?Adresse $adresse = null;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    // Utiliser 'self' au lieu de 'static' pour le retour du setter
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    // Utiliser 'self' au lieu de 'static' pour le retour du setter
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    // Le getter et le setter doivent être pour l'entité Adresse, pas pour une chaîne
    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    // Le paramètre du setter doit être de type ?Adresse pour accepter null
    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }
}
