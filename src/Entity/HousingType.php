<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Twig_Extension_Staging;
use Twig_Profiler_Dumper_Blackfire;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HousingTypeRepository")
 * @ORM\Table(name="ho_housing_type")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class HousingType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $min_stay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxclient;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_adults;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_children;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_babies;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordering;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $smoking;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_private;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Housing", mappedBy="housingtype")
     */
    private $housings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pricing", mappedBy="housingtype")
     */
    private $pricings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Equipment", inversedBy="housingTypes")
     * @ORM\JoinTable(name="ho_housing_type_equipment")
     */
    private $equipments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_enabled;


    public function __construct()
    {
        $this->equipments = new ArrayCollection();
        $this->housings = new ArrayCollection();
        $this->pricings = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMinStay(): ?int
    {
        return $this->min_stay;
    }

    public function setMinStay(?int $min_stay): self
    {
        $this->min_stay = $min_stay;

        return $this;
    }

    public function getMaxclient(): ?int
    {
        return $this->maxclient;
    }

    public function setMaxclient(?int $maxclient): self
    {
        $this->maxclient = $maxclient;

        return $this;
    }

    public function getMaxAdults(): ?int
    {
        return $this->max_adults;
    }

    public function setMaxAdults(?int $max_adults): self
    {
        $this->max_adults = $max_adults;

        return $this;
    }

    public function getMaxChildren(): ?int
    {
        return $this->max_children;
    }

    public function setMaxChildren(?int $max_children): self
    {
        $this->max_children = $max_children;

        return $this;
    }

    public function getMaxBabies(): ?int
    {
        return $this->max_babies;
    }

    public function setMaxBabies(?int $max_babies): self
    {
        $this->max_babies = $max_babies;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Equipment[]
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment): self
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments[] = $equipment;
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): self
    {
        if ($this->equipments->contains($equipment)) {
            $this->equipments->removeElement($equipment);
        }

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(?bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(?int $ordering): self
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getSmoking(): ?bool
    {
        return $this->smoking;
    }

    public function setSmoking(?bool $smoking): self
    {
        $this->smoking = $smoking;

        return $this;
    }

    public function getIsPrivate(): ?bool
    {
        return $this->is_private;
    }

    public function setIsPrivate(?bool $is_private): self
    {
        $this->is_private = $is_private;

        return $this;
    }

    /**
     * @return Collection|Housing[]
     */
    public function getHousings(): Collection
    {
        return $this->housings;
    }

    public function addHousing(Housing $housing): self
    {
        if (!$this->housings->contains($housing)) {
            $this->housings[] = $housing;
            $housing->setHousingtype($this);
        }

        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        if ($this->housings->contains($housing)) {
            $this->housings->removeElement($housing);
            // set the owning side to null (unless already changed)
            if ($housing->getHousingtype() === $this) {
                $housing->setHousingtype(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pricing[]
     */
    public function getPricings(): Collection
    {
        return $this->pricings;
    }

    public function addPricing(Pricing $pricing): self
    {
        if (!$this->pricings->contains($pricing)) {
            $this->pricings[] = $pricing;
            $pricing->setHousingtype($this);
        }

        return $this;
    }

    public function removePricing(Pricing $pricing): self
    {
        if ($this->pricings->contains($pricing)) {
            $this->pricings->removeElement($pricing);
            // set the owning side to null (unless already changed)
            if ($pricing->getHousingtype() === $this) {
                $pricing->setHousingtype(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    /**
     * Generates the magic method
     * 
     */
    public function __toString(){
        // to show the fullname of the User in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(?bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }
}
