<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagPlanConfigurationRepository")
 */
class TagPlanConfiguration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="tagPlanConfigurations")
     */
    private $tag;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PricingPlan", inversedBy="tagPlanConfigurations")
     */
    private $pricingplan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
        $this->pricingplan = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|PricingPlan[]
     */
    public function getPricingplan(): Collection
    {
        return $this->pricingplan;
    }

    public function addPricingplan(PricingPlan $pricingplan): self
    {
        if (!$this->pricingplan->contains($pricingplan)) {
            $this->pricingplan[] = $pricingplan;
        }

        return $this;
    }

    public function removePricingplan(PricingPlan $pricingplan): self
    {
        if ($this->pricingplan->contains($pricingplan)) {
            $this->pricingplan->removeElement($pricingplan);
        }

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
