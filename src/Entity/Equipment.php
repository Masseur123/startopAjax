<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipmentRepository")
 * @ORM\Table(name="ho_equipment")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Equipment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HousingType", mappedBy="equipments")
     */
    private $housingTypes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EquipmentCategory", inversedBy="equipments")
     */
    private $equipmentCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    public function __construct()
    {
        $this->housingTypes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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
     * @return Collection|HousingType[]
     */
    public function getHousingTypes(): Collection
    {
        return $this->housingTypes;
    }

    public function addHousingType(HousingType $housingType): self
    {
        if (!$this->housingTypes->contains($housingType)) {
            $this->housingTypes[] = $housingType;
            $housingType->addEquipment($this);
        }

        return $this;
    }

    public function removeHousingType(HousingType $housingType): self
    {
        if ($this->housingTypes->contains($housingType)) {
            $this->housingTypes->removeElement($housingType);
            $housingType->removeEquipment($this);
        }

        return $this;
    }

    public function getEquipmentCategory(): ?EquipmentCategory
    {
        return $this->equipmentCategory;
    }

    public function setEquipmentCategory(?EquipmentCategory $equipmentCategory): self
    {
        $this->equipmentCategory = $equipmentCategory;

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

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }
}
