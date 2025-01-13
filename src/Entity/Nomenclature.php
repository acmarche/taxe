<?php

namespace AcMarche\Taxe\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\Taxe\Repository\NomenclatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: NomenclatureRepository::class)]
class Nomenclature implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 120)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $position = null;

    /**
     * @var Collection<int, Taxe>
     */
    #[ORM\OneToMany(targetEntity: Taxe::class, mappedBy: 'nomenclature')]
    private iterable $taxes;

    public function __construct()
    {
        $this->taxes = new ArrayCollection();
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|Taxe[]
     */
    public function getTaxes(): iterable
    {
        return $this->taxes;
    }

    public function addTaxe(Taxe $taxe): self
    {
        if (!$this->taxes->contains($taxe)) {
            $this->taxes[] = $taxe;
            $taxe->setNomenclature($this);
        }

        return $this;
    }

    public function removeTaxe(Taxe $taxe): self
    {
        if ($this->taxes->contains($taxe)) {
            $this->taxes->removeElement($taxe);
            // set the owning side to null (unless already changed)
            if ($taxe->getNomenclature() === $this) {
                $taxe->setNomenclature(null);
            }
        }

        return $this;
    }

    public function addTax(Taxe $taxe): self
    {
        if (!$this->taxes->contains($taxe)) {
            $this->taxes[] = $taxe;
            $taxe->setNomenclature($this);
        }

        return $this;
    }

    public function removeTax(Taxe $taxe): self
    {
        // set the owning side to null (unless already changed)
        if ($this->taxes->removeElement($taxe) && $taxe->getNomenclature() === $this) {
            $taxe->setNomenclature(null);
        }

        return $this;
    }
}
