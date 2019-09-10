<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Twig_Extension_InitRuntimeInterface;
use Twig_Sandbox_SecurityNotAllowedPropertyError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParamCostCenterRepository")
 * @ORM\Table(name="bud_param_cost_center")
 * @UniqueEntity(
 *     fields={"year", "costcenter"},
 *     errorPath="costcenter",
 *     message="This budget is already allocate on that year."
 * )
 * 
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class ParamCostCenter
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
     */
    private $costcenter;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     * @Assert\NotBlank(
     *     message = " this amount will not be blank"
     * )
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2, nullable=true)
     */
    private $amount_realized;

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

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getYear(): ? Year
    {
        return $this->year;
    }

    public function setYear(? Year $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCostcenter(): ? CostCenter
    {
        return $this->costcenter;
    }

    public function setCostcenter(? CostCenter $costcenter): self
    {
        $this->costcenter = $costcenter;

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

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountRealized()
    {
        return $this->amount_realized;
    }

    public function setAmountRealized($amount_realized): self
    {
        $this->amount_realized = $amount_realized;

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

    public function getBranch(): ? Branch
    {
        return $this->branch;
    }

    public function setBranch(? Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }
}
