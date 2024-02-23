<?php

// src/Entity/Livre.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $titre = null;

    #[ORM\ManyToOne(targetEntity: Auteur::class, inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auteur $auteur = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $anneeParution = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePages = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $isbn = null; // Nouvelle propriété ISBN

    // Getters and setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getAnneeParution(): ?\DateTimeInterface
    {
        return $this->anneeParution;
    }

    public function setAnneeParution(?\DateTimeInterface $anneeParution): self
    {
        $this->anneeParution = $anneeParution;
        return $this;
    }

    public function getNombrePages(): ?int
    {
        return $this->nombrePages;
    }

    public function setNombrePages(?int $nombrePages): self
    {
        $this->nombrePages = $nombrePages;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;
        return $this;
    }
}
