<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TruckRouteRepository")
 */
class TruckRoute
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Store")
     * @ORM\JoinColumn(nullable=false)
     */
    private $store;
    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $max_time_allocation;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $delivery_charge;

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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Road", inversedBy="truck_route")
     */
    private $road;

    public function __construct()
    {
        $this->road = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }
    public function getMaxTimeAllocation(): ?string
    {
        return $this->max_time_allocation;
    }

    public function setMaxTimeAllocation(string $max_time_allocation): self
    {
        $this->max_time_allocation = $max_time_allocation;

        return $this;
    }

    public function getDeliveryCharge(): ?string
    {
        return $this->delivery_charge;
    }

    public function setDeliveryCharge(string $delivery_charge): self
    {
        $this->delivery_charge = $delivery_charge;

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

    /**
     * @return Collection|Road[]
     */
    public function getRoad(): Collection
    {
        return $this->road;
    }

    public function addRoad(Road $road): self
    {
        if (!$this->road->contains($road)) {
            $this->road[] = $road;
        }

        return $this;
    }

    public function removeRoad(Road $road): self
    {
        if ($this->road->contains($road)) {
            $this->road->removeElement($road);
        }

        return $this;
    }
}
