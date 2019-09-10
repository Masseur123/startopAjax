<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_TokenParser_Macro;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalesInvoiceRepository")
 * @ORM\Table(name="sales_invoice")
 */
class SalesInvoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $reference;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $invoiceAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider")
     */
    private $provider;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashDesk")
     */
    private $cashier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="datetime")
     */
    private $payAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $amount_tva;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     */
    private $payment_mode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_delay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reduction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $repayment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $escompte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     */
    private $currency;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_pay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $advance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLock;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isvalid;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SalesDelevery")
     */
    private $delevery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SalesOrder")
     */
    private $ord;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInvoiceAt(): ?\DateTimeInterface
    {
        return $this->invoiceAt;
    }

    public function setInvoiceAt(?\DateTimeInterface $invoiceAt): self
    {
        $this->invoiceAt = $invoiceAt;

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

    public function getClient(): ?Customer
    {
        return $this->client;
    }

    public function setClient(?Customer $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getPayAt(): ?\DateTimeInterface
    {
        return $this->payAt;
    }

    public function setPayAt(\DateTimeInterface $payAt): self
    {
        $this->payAt = $payAt;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountTva(): ?string
    {
        return $this->amount_tva;
    }

    public function setAmountTva(string $amount_tva): self
    {
        $this->amount_tva = $amount_tva;

        return $this;
    }

    public function getPaymentMode(): ?PaymentMethod
    {
        return $this->payment_mode;
    }

    public function setPaymentMode(?PaymentMethod $payment_mode): self
    {
        $this->payment_mode = $payment_mode;

        return $this;
    }

    public function getPaymentDelay(): ?string
    {
        return $this->payment_delay;
    }

    public function setPaymentDelay(string $payment_delay): self
    {
        $this->payment_delay = $payment_delay;

        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(string $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getRepayment(): ?string
    {
        return $this->repayment;
    }

    public function setRepayment(?string $repayment): self
    {
        $this->repayment = $repayment;

        return $this;
    }

    public function getEscompte(): ?string
    {
        return $this->escompte;
    }

    public function setEscompte(?string $escompte): self
    {
        $this->escompte = $escompte;

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

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getIsPay(): ?bool
    {
        return $this->is_pay;
    }

    public function setIsPay(?bool $is_pay): self
    {
        $this->is_pay = $is_pay;

        return $this;
    }

    public function getAdvance(): ?string
    {
        return $this->advance;
    }

    public function setAdvance(?string $advance): self
    {
        $this->advance = $advance;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(?string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getIsLock(): ?bool
    {
        return $this->isLock;
    }

    public function setIsLock(bool $isLock): self
    {
        $this->isLock = $isLock;

        return $this;
    }

    public function getIsvalid(): ?bool
    {
        return $this->isvalid;
    }

    public function setIsvalid(?bool $isvalid): self
    {
        $this->isvalid = $isvalid;

        return $this;
    }

    public function getDelevery(): ?SalesDelevery
    {
        return $this->delevery;
    }

    public function setDelevery(?SalesDelevery $delevery): self
    {
        $this->delevery = $delevery;

        return $this;
    }

    public function getOrd(): ?SalesOrder
    {
        return $this->ord;
    }

    public function setOrd(?SalesOrder $ord): self
    {
        $this->ord = $ord;

        return $this;
    }
}
