<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=4)
     */
    private $size;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity_in_stock;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $wholesale_price;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $retail_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $retail_limit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getQuantityInStock(): ?int
    {
        return $this->quantity_in_stock;
    }

    public function setQuantityInStock(int $quantity_in_stock): self
    {
        $this->quantity_in_stock = $quantity_in_stock;

        return $this;
    }

    public function getWholesalePrice(): ?string
    {
        return $this->wholesale_price;
    }

    public function setWholesalePrice(string $wholesale_price): self
    {
        $this->wholesale_price = $wholesale_price;

        return $this;
    }

    public function getRetailPrice(): ?string
    {
        return $this->retail_price;
    }

    public function setRetailPrice(string $retail_price): self
    {
        $this->retail_price = $retail_price;

        return $this;
    }

    public function getRetailLimit(): ?int
    {
        return $this->retail_limit;
    }

    public function setRetailLimit(int $retail_limit): self
    {
        $this->retail_limit = $retail_limit;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }
}
