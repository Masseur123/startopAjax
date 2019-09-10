<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_NodeVisitor_Escaper;
use Twig_NodeVisitor_Sandbox;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomPriceRepository")
 */
class CustomPrice
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
    private $housingType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $day;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PricingPlan")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pricingPlan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHousingType(): ?HousingType
    {
        return $this->housingType;
    }

    public function setHousingType(?HousingType $housingType): self
    {
        $this->housingType = $housingType;

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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPricingPlan(): ?PricingPlan
    {
        return $this->pricingPlan;
    }

    public function setPricingPlan(?PricingPlan $pricingPlan): self
    {
        $this->pricingPlan = $pricingPlan;

        return $this;
    }
}
