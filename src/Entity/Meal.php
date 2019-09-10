<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 * @ORM\Table(name="ho_meal")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Meal
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordering;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $on_checkin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $on_checkout;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $on_stay;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_enabled;

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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $daily_chargable;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mandatory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Extra", mappedBy="meal")
     */
    private $extras;

    public function __construct()
    {
        $this->extras = new ArrayCollection();
    }
    

    public function getId()
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

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(?int $ordering): self
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getOnCheckin(): ?bool
    {
        return $this->on_checkin;
    }

    public function setOnCheckin(?bool $on_checkin): self
    {
        $this->on_checkin = $on_checkin;

        return $this;
    }

    public function getOnCheckout(): ?bool
    {
        return $this->on_checkout;
    }

    public function setOnCheckout(?bool $on_checkout): self
    {
        $this->on_checkout = $on_checkout;

        return $this;
    }

    public function getOnStay(): ?bool
    {
        return $this->on_stay;
    }

    public function setOnStay(?bool $on_stay): self
    {
        $this->on_stay = $on_stay;

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

    public function getDailyChargable(): ?bool
    {
        return $this->daily_chargable;
    }

    public function setDailyChargable(?bool $daily_chargable): self
    {
        $this->daily_chargable = $daily_chargable;

        return $this;
    }

    public function getMandatory(): ?bool
    {
        return $this->mandatory;
    }

    public function setMandatory(?bool $mandatory): self
    {
        $this->mandatory = $mandatory;

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

    /**
     * @return Collection|Extra[]
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(Extra $extra): self
    {
        if (!$this->extras->contains($extra)) {
            $this->extras[] = $extra;
            $extra->addMeal($this);
        }

        return $this;
    }

    public function removeExtra(Extra $extra): self
    {
        if ($this->extras->contains($extra)) {
            $this->extras->removeElement($extra);
            $extra->removeMeal($this);
        }

        return $this;
    }
}
