<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Twig_NodeVisitor_SafeAnalysis;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransitRepository")
 * @ORM\Table(name="tra_transit")
 * @UniqueEntity("reference", message ="This transit already exist.")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 *
 */
class Transit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     *
     * @Assert\NotBlank
     *
     * @Assert\Length(
     *     min = 5,
     *     max = 255,
     *     minMessage = "Reference must be at least {{ limit }} characters long",
     *     maxMessage = "Reference cannot be longer than {{ limit }} characters"
     * )
     *
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $boat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank
     */
    private $countryfrom;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $comingfrom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank
     */
    private $countryto;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $goingto;

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
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_open;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransitHist", mappedBy="transit")
     */
    private $transitHists;

    public function __construct()
    {
        $this->transitHists = new ArrayCollection();
    }

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getReference(): ? string
    {
        return $this->reference;
    }

    public function setReference(? string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getBoat(): ? string
    {
        return $this->boat;
    }

    public function setBoat(? string $boat): self
    {
        $this->boat = $boat;

        return $this;
    }

    public function getComingfrom(): ? string
    {
        return $this->comingfrom;
    }

    public function setComingfrom(? string $comingfrom): self
    {
        $this->comingfrom = $comingfrom;

        return $this;
    }

    public function getCountryfrom(): ? Country
    {
        return $this->countryfrom;
    }

    public function setCountryfrom(? Country $countryfrom): self
    {
        $this->countryfrom = $countryfrom;

        return $this;
    }

    public function getGoingto(): ? string
    {
        return $this->goingto;
    }

    public function setGoingto(? string $goingto): self
    {
        $this->goingto = $goingto;

        return $this;
    }

    public function getCountryto(): ? Country
    {
        return $this->countryto;
    }

    public function setCountryto(? Country $countryto): self
    {
        $this->countryto = $countryto;

        return $this;
    }

    public function getCreatedAt(): ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ? User
    {
        return $this->user;
    }

    public function setUser(? User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Generates the magic method
     *
     */
    public function __toString()
    {
        // to show the reference of the Transit in the select
        return $this->getReference();
    }

    public function getIsOpen(): ? bool
    {
        return $this->is_open;
    }

    public function setIsOpen(bool $is_open): self
    {
        $this->is_open = $is_open;

        return $this;
    }

    public function getBranch(): ? Branch
    {
        return $this->branch;
    }

    public function setBranch(? Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return Collection|TransitHist[]
     */
    public function getTransitHists(): Collection
    {
        return $this->transitHists;
    }

    public function addTransitHist(TransitHist $transitHist): self
    {
        if (!$this->transitHists->contains($transitHist)) {
            $this->transitHists[] = $transitHist;
            $transitHist->setTransit($this);
        }

        return $this;
    }

    public function removeTransitHist(TransitHist $transitHist): self
    {
        if ($this->transitHists->contains($transitHist)) {
            $this->transitHists->removeElement($transitHist);
            // set the owning side to null (unless already changed)
            if ($transitHist->getTransit() === $this) {
                $transitHist->setTransit(null);
            }
        }

        return $this;
    }
}
