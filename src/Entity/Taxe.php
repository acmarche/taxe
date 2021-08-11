<?php

namespace AcMarche\Taxe\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AcMarche\Taxe\Repository\TaxeRepository")

 */
class Taxe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $position = null;

    /**
     * @ORM\ManyToOne(targetEntity="AcMarche\Taxe\Entity\Nomenclature", inversedBy="taxes")
     */
    private ?Nomenclature $nomenclature = null;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\Taxe\Entity\Exercice", mappedBy="taxe", orphanRemoval=true)
     */
    private Collection $exercices;

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
        $this->position = 0;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
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
    public function getExercices(): ArrayCollection
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
