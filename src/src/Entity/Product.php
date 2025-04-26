<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:create'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'product:update'])]
    private string $name;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups(['product:read', 'product:update'])]
    private string $price;

    #[ORM\Column]
    #[Groups(['product:read', 'product:update'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Groups(['product:read', 'product:update'])]
    private DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(targetEntity: ProductCategory::class, mappedBy: 'product', cascade: [
        'persist',
        'remove'
    ], orphanRemoval: true)]
    #[Groups(['product:read', 'product:update'])]
    private Collection $productCategories;

    public function __construct()
    {
        $this->productCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories->add($productCategory);
            $productCategory->setProduct($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            // Set the owning side to null (unless already changed)
            if ($productCategory->getProduct() === $this) {
                $productCategory->setProduct(null);
            }
        }

        return $this;
    }
}
