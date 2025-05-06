<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_menu', 'get_order'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_menu', 'get_order'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get_menu', 'get_order'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_menu', 'get_order'])]
    private ?string $category = null;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['get_menu', 'get_order'])]
    private ?bool $isAvailable = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get_menu', 'get_order'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get_menu', 'get_order'])]
    private ?string $image = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'Menu', orphanRemoval: true)]
    private Collection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setMenu($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        // set the owning side to null (unless already changed)
        if ($this->orderItems->removeElement($orderItem) && $orderItem->getMenu() === $this) {
            $orderItem->setMenu(null);
        }

        return $this;
    }
}
