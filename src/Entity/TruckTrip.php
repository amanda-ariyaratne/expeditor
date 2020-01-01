<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TruckTripRepository")
 */
class TruckTrip
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Truck")
     */
    private $truck;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver")
     
     * */
    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DriverAssistant")
     * @ORM\JoinColumn
     */
    public $driver_assistant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TruckRoute")
     * @ORM\JoinColumn(nullable=false)
     */
    public $truck_route;

    
    /**
     * @ORM\Column(type="time", nullable=true)
     */
    public $start_time;
/**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    
    

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
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTruck(): ?Truck
    {
        return $this->truck;
    }

    public function setTruck(?Truck $truck): self
    {
        $this->truck = $truck;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriverAssistant(): ?DriverAssistant
    {
        return $this->driver_assistant;
    }

    public function setDriverAssistant(?DriverAssistant $driver_assistant): self
    {
        $this->driver_assistant = $driver_assistant;

        return $this;
    }

    public function getTruckRoute(): ?TruckRoute
    {
        return $this->truck_route;
    }

    public function setTruckRoute(?TruckRoute $truck_route): self
    {
        $this->truck_route = $truck_route;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(?\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

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
