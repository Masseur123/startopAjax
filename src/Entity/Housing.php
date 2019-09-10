<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HousingRepository")
 * @ORM\Table(name="ho_housing")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Housing
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
    private $label;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_enabled;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HousingType", inversedBy="housings")
     */
    private $housingtype;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $price_adult;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $price_child;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pricing", mappedBy="housing")
     */
    private $pricings;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reservation", mappedBy="housings")
     */
    private $reservations;

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
        $this->pricings = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    public function getIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function getHousingtype(): ?HousingType
    {
        return $this->housingtype;
    }

    public function setHousingtype(?HousingType $housingtype): self
    {
        $this->housingtype = $housingtype;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceAdult()
    {
        return $this->price_adult;
    }

    public function setPriceAdult($price_adult): self
    {
        $this->price_adult = $price_adult;

        return $this;
    }

    public function getPriceChild()
    {
        return $this->price_child;
    }

    public function setPriceChild($price_child): self
    {
        $this->price_child = $price_child;

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
            $pricing->setHousing($this);
        }

        return $this;
    }

    public function removePricing(Pricing $pricing): self
    {
        if ($this->pricings->contains($pricing)) {
            $this->pricings->removeElement($pricing);
            // set the owning side to null (unless already changed)
            if ($pricing->getHousing() === $this) {
                $pricing->setHousing(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->addHousing($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            $reservation->removeHousing($this);
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

    /**
     * Generates the magic method
     * 
     */
    public function __toString(){
        // to show the fullname of the User in the select
        return $this->label;
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
}
