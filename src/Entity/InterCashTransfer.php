<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_Sandbox_SecurityNotAllowedMethodError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\")
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\InterCashTransferRepository")
 * @ORM\Table(name="tre_inter_cash_transfer")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class InterCashTransfer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank")
     */
    private $bankSrc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashDesk")
     */
    private $cashdeskSrc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank")
     */
    private $bankDes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashDesk")
     */
    private $cashdeskDes;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_valid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getBankSrc() : ? Bank
    {
        return $this->bankSrc;
    }

    public function setBankSrc(? Bank $bankSrc) : self
    {
        $this->bankSrc = $bankSrc;

        return $this;
    }

    public function getCashdeskSrc() : ? CashDesk
    {
        return $this->cashdeskSrc;
    }

    public function setCashdeskSrc(? CashDesk $cashdeskSrc) : self
    {
        $this->cashdeskSrc = $cashdeskSrc;

        return $this;
    }

    public function getBankDes() : ? Bank
    {
        return $this->bankDes;
    }

    public function setBankDes(? Bank $bankDes) : self
    {
        $this->bankDes = $bankDes;

        return $this;
    }

    public function getCashdeskDes() : ? CashDesk
    {
        return $this->cashdeskDes;
    }

    public function setCashdeskDes(? CashDesk $cashdeskDes) : self
    {
        $this->cashdeskDes = $cashdeskDes;

        return $this;
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

    public function getCreatedAt() : ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsValid() : ? bool
    {
        return $this->is_valid;
    }

    public function setIsValid(? bool $is_valid) : self
    {
        $this->is_valid = $is_valid;

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

    public function getUser() : ? User
    {
        return $this->user;
    }

    public function setUser(? User $user) : self
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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
}
