<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_order'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_order'])]
    private ?Menu $Menu = null;

    #[ORM\ManyToOne(inversedBy: 'orderItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EntireOrder $EntireOrder = null;

    #[ORM\Column]
    #[Groups(['get_order'])]
    private ?int $Quantity = null;

    #[ORM\Column]
    #[Groups(['get_order'])]
    private ?float $Price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenu(): ?Menu
    {
        return $this->Menu;
    }

    public function setMenu(?Menu $Menu): static
    {
        $this->Menu = $Menu;

        return $this;
    }

    public function getEntireOrder(): ?EntireOrder
    {
        return $this->EntireOrder;
    }

    public function setEntireOrder(?EntireOrder $EntireOrder): static
    {
        $this->EntireOrder = $EntireOrder;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): static
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): static
    {
        $this->Price = $Price;

        return $this;
    }
}
