<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_user', 'get_order'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['get_user', 'get_order'])]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['get_user'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_user'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_user'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_user'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_user'])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['get_user'])]
    private ?int $loyalty = null;

    /**
     * @var Collection<int, EntireOrder>
     */
    #[ORM\OneToMany(targetEntity: EntireOrder::class, mappedBy: 'user')]
    #[Groups(['get_user'])]
    private Collection $entireOrders;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get_user'])]
    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get_user'])]
    private ?string $city = null;

    #[ORM\Column(length: 2, nullable: true)]
    #[Groups(['get_user'])]
    private ?string $state = null;

    #[ORM\Column(length: 11, nullable: true)]
    #[Groups(['get_user'])]
    private ?string $zip = null;

    public function __construct()
    {
        $this->entireOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): static
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    #[Groups(['get_user'])]
    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->roles);
    }

    #[Groups(['get_user'])]
    public function isManager(): bool
    {
        return $this->isAdmin() || in_array('ROLE_MANAGER', $this->roles);
    }

    #[Groups(['get_user'])]
    public function isEmployee(): bool
    {
        return $this->isAdmin() || $this->isManager() || in_array('ROLE_EMPLOYEE', $this->roles);
    }

    public function __toString(): string
    {
        return (string) $this->username;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLoyalty(): ?int
    {
        return $this->loyalty;
    }

    public function setLoyalty(?int $loyalty): static
    {
        $this->loyalty = $loyalty;

        return $this;
    }

    /**
     * @return Collection<int, EntireOrder>
     */
    public function getEntireOrders(): Collection
    {
        return $this->entireOrders;
    }

    public function addEntireOrder(EntireOrder $entireOrder): static
    {
        if (!$this->entireOrders->contains($entireOrder)) {
            $this->entireOrders->add($entireOrder);
            $entireOrder->setUser($this);
        }

        return $this;
    }

    public function removeEntireOrder(EntireOrder $entireOrder): static
    {
        // set the owning side to null (unless already changed)
        if ($this->entireOrders->removeElement($entireOrder) && $entireOrder->getUser() === $this) {
            $entireOrder->setUser(null);
        }

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }
}
