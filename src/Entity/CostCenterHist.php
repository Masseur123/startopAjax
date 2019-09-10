<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_Extension_InitRuntimeInterface;
use Twig_Sandbox_SecurityNotAllowedMethodError;
use Twig_Sandbox_SecurityNotAllowedPropertyError;
use Twig_Sandbox_SecurityPolicyInterface;
use Twig_TokenParser_Macro;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CostCenterHistRepository")
 * @ORM\Table(name="bud_cost_center_hist")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class CostCenterHist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $doingAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_valid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Year")
     * @ORM\JoinColumn(nullable=false)
     */
    private $year;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cashpay;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\CostCenter")
     * @ORM\JoinColumn(nullable=false)
     */
    private $costcenter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax")
     */
    private $tax;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $taxamount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $payAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashDesk")
     */
    private $cashier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     */
    private $paymentmethod;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank")
     */
    private $bank;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount) : self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDoingAt() : ? \DateTimeInterface
    {
        return $this->doingAt;
    }

    public function setDoingAt(\DateTimeInterface $doingAt) : self
    {
        $this->doingAt = $doingAt;

        return $this;
    }

    public function getIsValid() : ? bool
    {
        return $this->is_valid;
    }

    public function setIsValid(bool $is_valid) : self
    {
        $this->is_valid = $is_valid;

        return $this;
    }

    public function getCurrency() : ? Currency
    {
        return $this->currency;
    }

    public function setCurrency(? Currency $currency) : self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getYear() : ? Year
    {
        return $this->year;
    }

    public function setYear(? Year $year) : self
    {
        $this->year = $year;

        return $this;
    }

    public function getCashpay() : ? bool
    {
        return $this->cashpay;
    }

    public function setCashpay(bool $cashpay) : self
    {
        $this->cashpay = $cashpay;

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

    public function getUser() : ? User
    {
        return $this->user;
    }

    public function setUser(? User $user) : self
    {
        $this->user = $user;

        return $this;
    }

    public function getCostcenter() : ? CostCenter
    {
        return $this->costcenter;
    }

    public function setCostcenter(? CostCenter $costcenter) : self
    {
        $this->costcenter = $costcenter;

        return $this;
    }

    public function getTax() : ? Tax
    {
        return $this->tax;
    }

    public function setTax(? Tax $tax) : self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getType() : ? string
    {
        return $this->type;
    }

    public function setType(string $type) : self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus() : ? string
    {
        return $this->status;
    }

    public function setStatus(string $status) : self
    {
        $this->status = $status;

        return $this;
    }

    public function getTaxamount()
    {
        return $this->taxamount;
    }

    public function setTaxamount($taxamount) : self
    {
        $this->taxamount = $taxamount;

        return $this;
    }

    public function getPayAt() : ? \DateTimeInterface
    {
        return $this->payAt;
    }

    public function setPayAt(? \DateTimeInterface $payAt) : self
    {
        $this->payAt = $payAt;

        return $this;
    }

    public function getCashier() : ? CashDesk
    {
        return $this->cashier;
    }

    public function setCashier(? CashDesk $cashier) : self
    {
        $this->cashier = $cashier;

        return $this;
    }

    public function getDescription() : ? string
    {
        return $this->description;
    }

    public function setDescription(? string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    public function getRefNumber() : ? string
    {
        return $this->refNumber;
    }

    public function setRefNumber(? string $refNumber) : self
    {
        $this->refNumber = $refNumber;

        return $this;
    }

    public function getPaymentmethod() : ? PaymentMethod
    {
        return $this->paymentmethod;
    }

    public function setPaymentmethod(? PaymentMethod $paymentmethod) : self
    {
        $this->paymentmethod = $paymentmethod;

        return $this;
    }

    public function getBank() : ? Bank
    {
        return $this->bank;
    }

    public function setBank(? Bank $bank) : self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getBranch() : ? Branch
    {
        return $this->branch;
    }

    public function setBranch(? Branch $branch) : self
    {
        $this->branch = $branch;

        return $this;
    }
}
