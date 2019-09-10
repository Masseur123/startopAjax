<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Twig_TokenParser_Do;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="ho_tag")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Tag
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
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TagCategory")
     */
    private $tagcategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PricingPlan", inversedBy="tags")
     */
    private $princingplan;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TagPlanConfiguration", mappedBy="tag")
     */
    private $tagPlanConfigurations;

    public function __construct()
    {
        $this->princingplan = new ArrayCollection();
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

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

    public function getTagcategory(): ?TagCategory
    {
        return $this->tagcategory;
    }

    public function setTagcategory(?TagCategory $tagcategory): self
    {
        $this->tagcategory = $tagcategory;

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
     * @return Collection|PricingPlan[]
     */
    public function getPrincingplan(): Collection
    {
        return $this->princingplan;
    }

    public function addPrincingplan(PricingPlan $princingplan): self
    {
        if (!$this->princingplan->contains($princingplan)) {
            $this->princingplan[] = $princingplan;
        }

        return $this;
    }

    public function removePrincingplan(PricingPlan $princingplan): self
    {
        if ($this->princingplan->contains($princingplan)) {
            $this->princingplan->removeElement($princingplan);
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
            $tagPlanConfiguration->addTag($this);
        }

        return $this;
    }

    public function removeTagPlanConfiguration(TagPlanConfiguration $tagPlanConfiguration): self
    {
        if ($this->tagPlanConfigurations->contains($tagPlanConfiguration)) {
            $this->tagPlanConfigurations->removeElement($tagPlanConfiguration);
            $tagPlanConfiguration->removeTag($this);
        }

        return $this;
    }
}
