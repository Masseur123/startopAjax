<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Twig_TokenParser_Include;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ContainerRepository")
 * @ORM\Table(name="tra_container")
 *
 * @UniqueEntity(
 *     fields={"reference"},
 *     message="This reference already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Container
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=12, nullable=true, unique=true)
     * @Assert\Length(min=6, max=12)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Batch")
     * @ORM\JoinColumn(nullable=false)
     */
    private $batch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Containerlength")
     * @ORM\JoinColumn(nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(min=6, max=50)
     */
    private $plumb;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_certified;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ContainerTracking", mappedBy="containers")
     */
    private $containerTrackings;

    public function __construct()
    {
        $this->containerTrackings = new ArrayCollection();
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getReference() : ? string
    {
        return $this->reference;
    }

    public function setReference(string $reference) : self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCreatedAt() : ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBatch() : ? Batch
    {
        return $this->batch;
    }

    public function setBatch(? Batch $batch) : self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getUser() : ? User
    {
        return $this->user;
    }

    public function setUser(? User $user) : self
    {
        $this->user = $user;

        return $this;
    }

    public function getLength() : ? Containerlength
    {
        return $this->length;
    }

    public function setLength(? Containerlength $length) : self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Generates the magic method
     */
    public function __toString()
    {
        // to show the fullname of the User in the select
        if ($this->reference) {
            return $this->reference;
        } else {
            return "";
        }
    }

    public function getPlumb() : ? string
    {
        return $this->plumb;
    }

    public function setPlumb(? string $plumb) : self
    {
        $this->plumb = $plumb;

        return $this;
    }

    public function getIsCertified() : ? bool
    {
        return $this->is_certified;
    }

    public function setIsCertified(? bool $is_certified) : self
    {
        $this->is_certified = $is_certified;

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
     * @return Collection|ContainerTracking[]
     */
    public function getContainerTrackings(): Collection
    {
        return $this->containerTrackings;
    }

    public function addContainerTracking(ContainerTracking $containerTracking): self
    {
        if (!$this->containerTrackings->contains($containerTracking)) {
            $this->containerTrackings[] = $containerTracking;
            $containerTracking->addContainer($this);
        }

        return $this;
    }

    public function removeContainerTracking(ContainerTracking $containerTracking): self
    {
        if ($this->containerTrackings->contains($containerTracking)) {
            $this->containerTrackings->removeElement($containerTracking);
            $containerTracking->removeContainer($this);
        }

        return $this;
    }
}
