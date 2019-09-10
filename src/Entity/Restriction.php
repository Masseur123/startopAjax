<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Twig_NodeVisitor_Escaper;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestrictionRepository")
 */
class Restriction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HousingType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $housingtype;

    /**
     * @ORM\Column(type="datetime")
     */
    private $day;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\RestrictionConfiguration", mappedBy="restriction")
     */
    private $restrictionConfigurations;

    public function __construct()
    {
        $this->restrictionConfigurations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return Collection|RestrictionConfiguration[]
     */
    public function getRestrictionConfigurations(): Collection
    {
        return $this->restrictionConfigurations;
    }

    public function addRestrictionConfiguration(RestrictionConfiguration $restrictionConfiguration): self
    {
        if (!$this->restrictionConfigurations->contains($restrictionConfiguration)) {
            $this->restrictionConfigurations[] = $restrictionConfiguration;
            $restrictionConfiguration->addRestriction($this);
        }

        return $this;
    }

    public function removeRestrictionConfiguration(RestrictionConfiguration $restrictionConfiguration): self
    {
        if ($this->restrictionConfigurations->contains($restrictionConfiguration)) {
            $this->restrictionConfigurations->removeElement($restrictionConfiguration);
            $restrictionConfiguration->removeRestriction($this);
        }

        return $this;
    }
}
