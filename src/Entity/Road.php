<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoadRepository")
 */
class Road
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
     * @ORM\ManyToMany(targetEntity="App\Entity\TruckRoute", mappedBy="road")
     */
    private $truck_route;

    public function __construct()
    {
        $this->truck_route = new ArrayCollection();
    }

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
     * @return Collection|TruckRoute[]
     */
    public function getTruckRoute(): Collection
    {
        return $this->truck_route;
    }

    public function addTruckRoute(TruckRoute $truckRoute): self
    {
        if (!$this->truck_route->contains($truckRoute)) {
            $this->truck_route[] = $truckRoute;
            $truckRoute->addRoad($this);
        }

        return $this;
    }

    public function removeTruckRoute(TruckRoute $truckRoute): self
    {
        if ($this->truck_route->contains($truckRoute)) {
            $this->truck_route->removeElement($truckRoute);
            $truckRoute->removeRoad($this);
        }

        return $this;
    }
}
