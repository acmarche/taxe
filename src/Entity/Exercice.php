<?php

namespace AcMarche\Taxe\Entity;

use AcMarche\Taxe\Repository\ExerciceRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    #[ORM\Column(type: 'string')]
    private ?string $annee = null;
    #[ORM\ManyToOne(targetEntity: Taxe::class, inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Taxe $taxe = null;
    #[ORM\Column(type: 'string', length: 150)]
    private ?string $fileName = null;
    #[Vich\UploadableField(mapping: 'taxes', fileNameProperty: 'fileName', size: 'fileSize')]
    private ?File $file = null;
    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $updatedAt;
    private ?string $nom = null;
    //api demande ??
    private ?int $position = null;

    //api demande ??
    public function __construct()
    {
        $this->updatedAt = new DateTime();
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getTaxe(): ?Taxe
    {
        return $this->taxe;
    }

    public function setTaxe(?Taxe $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        if (null !== $file) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new DateTime('now');
        }

        $this->file = $file;

        return $this;
    }

    /**
     * @return DateTime|DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
