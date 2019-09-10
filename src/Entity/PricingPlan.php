<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Twig_NodeVisitor_Escaper;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PricingPlanRepository")
 * @ORM\Table(name="ho_pricing_plan")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class PricingPlan
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pricing")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pricing;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $reduction;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\HousingType", inversedBy="princingplan")
     */
    private $housingType;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="princingplan")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TagPlanConfiguration", mappedBy="pricingplan")
     */
    private $tagPlanConfigurations;

    public function __construct()
    {
        $this->pricings = new ArrayCollection();
        $this->housingType = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->tagPlanConfigurations = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getPricing(): ?Pricing
    {
        return $this->pricing;
    }

    public function setPricing(?Pricing $pricing): self
    {
        $this->pricing = $pricing;

        return $this;
    }

    public function getReduction()
    {
        return $this->reduction;
    }

    public function setReduction($reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * @return Collection|HousingType[]
     */
    public function getHousingType(): Collection
    {
        return $this->housingType;
    }

    public function addHousingType(HousingType $housingType): self
    {
        if (!$this->housingType->contains($housingType)) {
            $this->housingType[] = $housingType;
        }

        return $this;
    }

    public function removeHousingType(HousingType $housingType): self
    {
        if ($this->housingType->contains($housingType)) {
            $this->housingType->removeElement($housingType);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPrincingplan($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePrincingplan($this);
        }

        return $this;
    }

    /**
     * @return Collection|TagPlanConfiguration[]
     */
    public function getTagPlanConfigurations(): Collection
    {
        return $this->tagPlanConfigurations;
    }

    public function addTagPlanConfiguration(TagPlanConfiguration $tagPlanConfiguration): self
    {
        if (!$this->tagPlanConfigurations->contains($tagPlanConfiguration)) {
            $this->tagPlanConfigurations[] = $tagPlanConfiguration;
            $tagPlanConfiguration->addPricingplan($this);
        }

        return $this;
    }

    public function removeTagPlanConfiguration(TagPlanConfiguration $tagPlanConfiguration): self
    {
        if ($this->tagPlanConfigurations->contains($tagPlanConfiguration)) {
            $this->tagPlanConfigurations->removeElement($tagPlanConfiguration);
            $tagPlanConfiguration->removePricingplan($this);
        }

        return $this;
    }
}
