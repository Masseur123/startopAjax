<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Twig_Profiler_Dumper_Blackfire;
use Twig_Profiler_Node_EnterProfile;
use Twig_Sandbox_SecurityPolicyInterface;
use Twig_TokenParser_Macro;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\Table(name="ho_reservation")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     */
    private $paymentmethod;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $payment_method_surcharge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payment_status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $payment_data;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $checkin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $checkout;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency")
     */
    private $currency;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_price;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_price_tax_inc;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_price_tax_exc;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_extra_price;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_extra_price_tax_inc;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_extra_price_tax_exc;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_discount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $deposit_amount;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_paid;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $tax_amount;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $tourist_tax_amount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $booking_type;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $total_single_supplement;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SourceOfReservation", inversedBy="reservations")
     */
    private $sourceofreservation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_approved;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Extra", inversedBy="reservations")
     * @ORM\JoinTable(name="ho_reservation_extra")
     */
    private $extras;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Housing", inversedBy="reservations")
     * @ORM\JoinTable(name="ho_reservation_housing")
     */
    private $housings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tax")
     * @ORM\JoinTable(name="ho_reservation_tax")
     */
    private $taxes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    public function __construct()
    {
        $this->extras = new ArrayCollection();
        $this->housings = new ArrayCollection();
        $this->taxes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getPaymentmethod(): ?PaymentMethod
    {
        return $this->paymentmethod;
    }

    public function setPaymentmethod(?PaymentMethod $paymentmethod): self
    {
        $this->paymentmethod = $paymentmethod;

        return $this;
    }

    public function getPaymentMethodSurcharge()
    {
        return $this->payment_method_surcharge;
    }

    public function setPaymentMethodSurcharge($payment_method_surcharge): self
    {
        $this->payment_method_surcharge = $payment_method_surcharge;

        return $this;
    }

    public function getPaymentStatus(): ?bool
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(bool $payment_status): self
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    public function getPaymentData(): ?string
    {
        return $this->payment_data;
    }

    public function setPaymentData(?string $payment_data): self
    {
        $this->payment_data = $payment_data;

        return $this;
    }

    public function getCheckin(): ?\DateTimeInterface
    {
        return $this->checkin;
    }

    public function setCheckin(?\DateTimeInterface $checkin): self
    {
        $this->checkin = $checkin;

        return $this;
    }

    public function getCheckout(): ?\DateTimeInterface
    {
        return $this->checkout;
    }

    public function setCheckout(?\DateTimeInterface $checkout): self
    {
        $this->checkout = $checkout;

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

    public function getTotalPrice()
    {
        return $this->total_price;
    }

    public function setTotalPrice($total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getTotalPriceTaxInc()
    {
        return $this->total_price_tax_inc;
    }

    public function setTotalPriceTaxInc($total_price_tax_inc): self
    {
        $this->total_price_tax_inc = $total_price_tax_inc;

        return $this;
    }

    public function getTotalPriceTaxExc()
    {
        return $this->total_price_tax_exc;
    }

    public function setTotalPriceTaxExc($total_price_tax_exc): self
    {
        $this->total_price_tax_exc = $total_price_tax_exc;

        return $this;
    }

    public function getTotalExtraPrice()
    {
        return $this->total_extra_price;
    }

    public function setTotalExtraPrice($total_extra_price): self
    {
        $this->total_extra_price = $total_extra_price;

        return $this;
    }

    public function getTotalExtraPriceTaxInc()
    {
        return $this->total_extra_price_tax_inc;
    }

    public function setTotalExtraPriceTaxInc($total_extra_price_tax_inc): self
    {
        $this->total_extra_price_tax_inc = $total_extra_price_tax_inc;

        return $this;
    }

    public function getTotalExtraPriceTaxExc()
    {
        return $this->total_extra_price_tax_exc;
    }

    public function setTotalExtraPriceTaxExc($total_extra_price_tax_exc): self
    {
        $this->total_extra_price_tax_exc = $total_extra_price_tax_exc;

        return $this;
    }

    public function getTotalDiscount()
    {
        return $this->total_discount;
    }

    public function setTotalDiscount($total_discount): self
    {
        $this->total_discount = $total_discount;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDepositAmount()
    {
        return $this->deposit_amount;
    }

    public function setDepositAmount($deposit_amount): self
    {
        $this->deposit_amount = $deposit_amount;

        return $this;
    }

    public function getTotalPaid()
    {
        return $this->total_paid;
    }

    public function setTotalPaid($total_paid): self
    {
        $this->total_paid = $total_paid;

        return $this;
    }

    public function getTaxAmount()
    {
        return $this->tax_amount;
    }

    public function setTaxAmount($tax_amount): self
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getTouristTaxAmount()
    {
        return $this->tourist_tax_amount;
    }

    public function setTouristTaxAmount($tourist_tax_amount): self
    {
        $this->tourist_tax_amount = $tourist_tax_amount;

        return $this;
    }

    public function getBookingType(): ?bool
    {
        return $this->booking_type;
    }

    public function setBookingType(?bool $booking_type): self
    {
        $this->booking_type = $booking_type;

        return $this;
    }

    public function getTotalSingleSupplement()
    {
        return $this->total_single_supplement;
    }

    public function setTotalSingleSupplement($total_single_supplement): self
    {
        $this->total_single_supplement = $total_single_supplement;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getSourceofreservation(): ?SourceOfReservation
    {
        return $this->sourceofreservation;
    }

    public function setSourceofreservation(?SourceOfReservation $sourceofreservation): self
    {
        $this->sourceofreservation = $sourceofreservation;

        return $this;
    }

    public function getIsApproved(): ?bool
    {
        return $this->is_approved;
    }

    public function setIsApproved(bool $is_approved): self
    {
        $this->is_approved = $is_approved;

        return $this;
    }

    /**
     * @return Collection|Extra[]
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(Extra $extra): self
    {
        if (!$this->extras->contains($extra)) {
            $this->extras[] = $extra;
        }

        return $this;
    }

    public function removeExtra(Extra $extra): self
    {
        if ($this->extras->contains($extra)) {
            $this->extras->removeElement($extra);
        }

        return $this;
    }

    /**
     * @return Collection|Housing[]
     */
    public function getHousings(): Collection
    {
        return $this->housings;
    }

    public function addHousing(Housing $housing): self
    {
        if (!$this->housings->contains($housing)) {
            $this->housings[] = $housing;
        }

        return $this;
    }

    public function removeHousing(Housing $housing): self
    {
        if ($this->housings->contains($housing)) {
            $this->housings->removeElement($housing);
        }

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

    /**
     * @return Collection|Tax[]
     */
    public function getTaxes(): Collection
    {
        return $this->taxes;
    }

    public function addTax(Tax $tax): self
    {
        if (!$this->taxes->contains($tax)) {
            $this->taxes[] = $tax;
        }

        return $this;
    }

    public function removeTax(Tax $tax): self
    {
        if ($this->taxes->contains($tax)) {
            $this->taxes->removeElement($tax);
        }

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
