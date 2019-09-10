<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_Sandbox_SecurityNotAllowedMethodError;
use Twig_Sandbox_SecurityNotAllowedPropertyError;
use Twig_TokenParser_Macro;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransitHistRepository")
 * @ORM\Table(name="tra_transit_hist")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class TransitHist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transit", inversedBy="transitHists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentFile", inversedBy="transitHists")
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax")
     */
    private $tax;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashDesk")
     */
    private $cashier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank")
     */
    private $bank;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     * @ORM\JoinColumn(nullable=false)
     */
    private $branch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     * @ORM\JoinColumn(nullable=true)
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $taxamount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_valid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_cash;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $payAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransit(): ?Transit
    {
        return $this->transit;
    }

    public function setTransit(?Transit $transit): self
    {
        $this->transit = $transit;

        return $this;
    }

    public function getDocument(): ?DocumentFile
    {
        return $this->document;
    }

    public function setDocument(?DocumentFile $document): self
    {
        $this->document = $document;

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

    public function getTax(): ?Tax
    {
        return $this->tax;
    }

    public function setTax(?Tax $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTaxamount()
    {
        return $this->taxamount;
    }

    public function setTaxamount($taxamount): self
    {
        $this->taxamount = $taxamount;

        return $this;
    }

    public function getCashier(): ?CashDesk
    {
        return $this->cashier;
    }

    public function setCashier(?CashDesk $cashier): self
    {
        $this->cashier = $cashier;

        return $this;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getIsValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(?bool $is_valid): self
    {
        $this->is_valid = $is_valid;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
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

    public function getIsCash(): ?bool
    {
        return $this->is_cash;
    }

    public function setIsCash(bool $is_cash): self
    {
        $this->is_cash = $is_cash;

        return $this;
    }

    public function getPayAt(): ?\DateTimeInterface
    {
        return $this->payAt;
    }

    public function setPayAt(?\DateTimeInterface $payAt): self
    {
        $this->payAt = $payAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }
}
