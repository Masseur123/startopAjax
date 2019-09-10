<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Twig_Extension_Sandbox;
use Twig_TokenParser_Macro;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 * @ORM\Table(name="bil_invoice")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Invoice
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
    private $invoicedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider")
     * @ORM\JoinColumn(nullable=true)
     */
    private $provider;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delevery")
     * @ORM\JoinColumn(nullable=true)
     */
    private $delevery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashDesk")
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod", inversedBy="invoices")
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $branch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Order")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ord;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLock;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;

    public function getId()
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getInvoicedAt(): ?\DateTimeInterface
    {
        return $this->invoicedAt;
    }

    public function setInvoicedAt(?\DateTimeInterface $invoicedAt): self
    {
        $this->invoicedAt = $invoicedAt;

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

    public function getDelevery(): ?Delevery
    {
        return $this->delevery;
    }

    public function setDelevery(?Delevery $delevery): self
    {
        $this->delevery = $delevery;

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

    public function setAmountTva(?string $amount_tva): self
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

    public function setReduction(?string $reduction): self
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

    public function getOrd(): ?Order
    {
        return $this->ord;
    }

    public function setOrd(?Order $ord): self
    {
        $this->ord = $ord;

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

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }


}
