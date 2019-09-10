<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_NodeVisitor_Escaper;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PricingRepository")
 * @ORM\Table(name="ho_pricing")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Pricing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HousingType", inversedBy="pricings")
     */
    private $housingtype;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $simple_price;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $extra_adult;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $extra_child;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $extra_baby;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId()
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getSimplePrice()
    {
        return $this->simple_price;
    }

    public function setSimplePrice($simple_price): self
    {
        $this->simple_price = $simple_price;

        return $this;
    }

    public function getExtraAdult()
    {
        return $this->extra_adult;
    }

    public function setExtraAdult($extra_adult): self
    {
        $this->extra_adult = $extra_adult;

        return $this;
    }

    public function getExtraChild()
    {
        return $this->extra_child;
    }

    public function setExtraChild($extra_child): self
    {
        $this->extra_child = $extra_child;

        return $this;
    }

    public function getExtraBaby()
    {
        return $this->extra_baby;
    }

    public function setExtraBaby($extra_baby): self
    {
        $this->extra_baby = $extra_baby;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
