<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\DriverRepository")
 */
class Driver
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=12)
     */
    private $NIC;
    /**
     * @ORM\Column(type="string", length=8)
     */
    private $license_no;
    /**
     * @ORM\Column(type="string", length=50)
     */
    public $first_name;
    /**
     * @ORM\Column(type="string", length=50)
     */
    public $last_name;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Store")
     * @ORM\JoinColumn(nullable=true)
     */
    private $store;
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
    private $worked_hours;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getNIC(): ?string
    {
        return $this->NIC;
    }
    public function setNIC(string $NIC): self
    {
        $this->NIC = $NIC;
        return $this;
    }
    public function getLicenseNo(): ?string
    {
        return $this->license_no;
    }
    public function setLicenseNo(string $license_no): self
    {
        $this->license_no = $license_no;
        return $this;
    }
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }
    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }
    public function getLastName(): ?string
    {
        return $this->last_name;
    }
    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;
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
    public function getWorkedHours(): ?int
    {
        return $this->worked_hours;
    }
    public function setWorkedHours(int $worked_hours): self
    {
        $this->worked_hours = $worked_hours;
        return $this;
    }
}