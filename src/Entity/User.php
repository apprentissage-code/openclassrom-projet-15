<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column]
  private ?string $name;

  #[ORM\Column(type: 'text', nullable: true)]
  private ?string $description;

  #[ORM\Column(length: 180, unique: true)]
  private ?string $email = null;

  #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'user', cascade: ['remove'])]
  private Collection $medias;

  #[ORM\Column]
  private array $roles = [];

  #[ORM\Column]
  private string $password;

  #[ORM\Column(type: 'boolean', options: ['default' => false])]
  private bool $isBlocked = false;

  public function __construct()
  {
    $this->medias = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(?string $name): void
  {
    $this->name = $name;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): void
  {
    $this->description = $description;
  }

  public function getMedias(): Collection
  {
    return $this->medias;
  }

  public function setMedias(Collection $medias): void
  {
    $this->medias = $medias;
  }

  public function getRoles(): array
  {
    $roles = $this->roles;

    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): void
  {
    $this->roles = $roles;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): void
  {
    $this->password = $password;
  }

  public function getUserIdentifier(): string
  {
    return $this->email;
  }

  public function eraseCredentials(): void {}

  public function isBlocked(): bool
  {
    return $this->isBlocked;
  }

  public function setIsBlocked(bool $isBlocked): self
  {
    $this->isBlocked = $isBlocked;
    return $this;
  }
}
