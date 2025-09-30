<?php

namespace AcMarche\Taxe\Entity;

use Doctrine\DBAL\Types\Types;
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
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    public ?string $annee = null;

    #[ORM\ManyToOne(targetEntity: Taxe::class, inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Taxe $taxe = null;

    #[ORM\Column(type: Types::STRING, length: 150)]
    public ?string $fileName = null;

    #[ORM\Column(type: Types::INTEGER)]
    public ?int $fileSize = null;

    #[Vich\UploadableField(mapping: 'taxes', fileNameProperty: 'fileName', size: 'fileSize')]
    public ?File $file = null;

    #[ORM\Column(type: Types::STRING, length: 200, nullable: true)]
    public ?string $url = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public DateTimeInterface $updatedAt;

    public ?string $nom = null;

    //api demande ??
    public ?int $position = null;

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
        if ($file instanceof File) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new DateTime('now');
        }

        $this->file = $file;

        return $this;
    }

    /**
     * @return DateTime|DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
