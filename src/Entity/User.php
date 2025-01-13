<?php

namespace AcMarche\Taxe\Entity;

use Doctrine\DBAL\Types\Types;
use AcMarche\Taxe\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, Stringable, PasswordAuthenticatedUserInterface
{
    use NameEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $email = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    private ?string $plain_password = null;

    public function __toString(): string
    {
        return $this->nom.' '.$this->prenom;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    public function setPlainPassword(?string $plain_password): void
    {
        $this->plain_password = $plain_password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if (\in_array($role, $this->roles, true)) {
            $index = array_search($role, $this->roles, true);
            unset($this->roles[$index]);
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return \in_array($role, $this->getRoles(), true);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): void
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
