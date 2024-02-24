<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuteurRepository;

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    // Déclaration de la propriété $livres pour la relation OneToMany
    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Livre::class)]
    private Collection $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    // Méthode pour ajouter un livre à la collection
    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }
        return $this;
    }

    // Méthode pour retirer un livre de la collection
    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // Si le livre était associé à cet auteur, le désassocier
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }
        return $this;
    }

    /**
     * @param string $letter La lettre par laquelle filtrer les livres
     * @return Collection|Livre[]
     */
    public function getLivresByFirstLetter(string $letter): Collection
    {
        // Filtrer les livres de l'auteur commençant par la lettre spécifiée
        return $this->livres->filter(function($livre) use ($letter) {
            return stripos($livre->getTitre(), $letter) === 0;
        });
    }
}
