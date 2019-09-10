<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestrictionConfigurationRepository")
 */
class RestrictionConfiguration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\RestrictionType", inversedBy="restrictionConfigurations")
     */
    private $restrictiontype;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Restriction", inversedBy="restrictionConfigurations")
     */
    private $restriction;

    public function __construct()
    {
        $this->restrictiontype = new ArrayCollection();
        $this->restriction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|RestrictionType[]
     */
    public function getRestrictiontype(): Collection
    {
        return $this->restrictiontype;
    }

    public function addRestrictiontype(RestrictionType $restrictiontype): self
    {
        if (!$this->restrictiontype->contains($restrictiontype)) {
            $this->restrictiontype[] = $restrictiontype;
        }

        return $this;
    }

    public function removeRestrictiontype(RestrictionType $restrictiontype): self
    {
        if ($this->restrictiontype->contains($restrictiontype)) {
            $this->restrictiontype->removeElement($restrictiontype);
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

    /**
     * @return Collection|Restriction[]
     */
    public function getRestriction(): Collection
    {
        return $this->restriction;
    }

    public function addRestriction(Restriction $restriction): self
    {
        if (!$this->restriction->contains($restriction)) {
            $this->restriction[] = $restriction;
        }

        return $this;
    }

    public function removeRestriction(Restriction $restriction): self
    {
        if ($this->restriction->contains($restriction)) {
            $this->restriction->removeElement($restriction);
        }

        return $this;
    }
}
