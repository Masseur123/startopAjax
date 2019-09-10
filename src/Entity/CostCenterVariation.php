<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_Extension_InitRuntimeInterface;
use Twig_Sandbox_SecurityNotAllowedPropertyError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CostCenterVariationRepository")
 * @ORM\Table(name="bud_cost_center_variation")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class CostCenterVariation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Year")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CostCenter")
     * @ORM\JoinColumn(nullable=false)
     */
    private $costcenter;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_increase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIsIncrease(): ?bool
    {
        return $this->is_increase;
    }

    public function setIsIncrease(bool $is_increase): self
    {
        $this->is_increase = $is_increase;

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

    public function getCostcenter(): ?CostCenter
    {
        return $this->costcenter;
    }

    public function setCostcenter(?CostCenter $costcenter): self
    {
        $this->costcenter = $costcenter;

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
}
