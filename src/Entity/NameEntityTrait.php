<?php

namespace AcMarche\Taxe\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait NameEntityTrait
{
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $prenom = null;

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
}
