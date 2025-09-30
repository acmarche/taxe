<?php

namespace AcMarche\Taxe\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\Taxe\Repository\TaxeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: TaxeRepository::class)]
class Taxe implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 200)]
    public ?string $nom = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    public ?int $position = 0;

    #[ORM\ManyToOne(targetEntity: Nomenclature::class, inversedBy: 'taxes')]
    public ?Nomenclature $nomenclature = null;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'taxe', orphanRemoval: true)]
    public iterable $exercices;

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->nom;
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

    public function getNomenclature(): ?Nomenclature
    {
        return $this->nomenclature;
    }

    public function setNomenclature(?Nomenclature $nomenclature): self
    {
        $this->nomenclature = $nomenclature;

        return $this;
    }

    /**
     * @return Collection|Exercice[]
     */
    public function getExercices(): iterable
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): self
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices[] = $exercice;
            $exercice->setTaxe($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): self
    {
        // set the owning side to null (unless already changed)
        if ($this->exercices->removeElement($exercice) && $exercice->getTaxe() === $this) {
            $exercice->setTaxe(null);
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
