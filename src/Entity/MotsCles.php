<?php

namespace App\Entity;

use App\Repository\MotsClesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotsClesRepository::class)]
class MotsCles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $motCle = null;

    #[ORM\ManyToMany(targetEntity: MarquePage::class, mappedBy: 'motsCles')]
    private Collection $marquePages;

    public function __construct()
    {
        $this->marquePages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotCle(): ?string
    {
        return $this->motCle;
    }

    public function setMotCle(string $motCle): static
    {
        $this->motCle = $motCle;

        return $this;
    }

    /**
     * @return Collection<int, MarquePage>
     */
    public function getMarquePages(): Collection
    {
        return $this->marquePages;
    }

    public function addMarquePage(MarquePage $marquePage): static
    {
        if (!$this->marquePages->contains($marquePage)) {
            $this->marquePages->add($marquePage);
            $marquePage->addMotsCle($this);
        }

        return $this;
    }

    public function removeMarquePage(MarquePage $marquePage): static
    {
        if ($this->marquePages->removeElement($marquePage)) {
            $marquePage->removeMotsCle($this);
        }

        return $this;
    }
}
