<?php

namespace AcMarche\Taxe\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AcMarche\Taxe\Repository\NomenclatureRepository")

 */
class Nomenclature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $position = null;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\Taxe\Entity\Taxe", mappedBy="nomenclature")
     */
    private iterable $taxes;

    public function __construct()
    {
        $this->taxes = new ArrayCollection();
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

    public function addTax(Taxe $tax): self
    {
        if (!$this->taxes->contains($tax)) {
            $this->taxes[] = $tax;
            $tax->setNomenclature($this);
        }

        return $this;
    }

    public function removeTax(Taxe $tax): self
    {
        // set the owning side to null (unless already changed)
        if ($this->taxes->removeElement($tax) && $tax->getNomenclature() === $this) {
            $tax->setNomenclature(null);
        }

        return $this;
    }
}
