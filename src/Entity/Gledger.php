<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_Sandbox_SecurityNotAllowedPropertyError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GledgerRepository")
 * @ORM\Table(name="act_gledger")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Gledger
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Journal")
     */
    private $journal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transactionNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2, nullable=true)
     */
    private $debit;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=2, nullable=true)
     */
    private $credit;

    /**
     * @ORM\Column(type="datetime")
     */
    private $doingAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $writeAt;

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
    private $is_sent;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDebit()
    {
        return $this->debit;
    }

    public function setDebit($debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    public function getCredit()
    {
        return $this->credit;
    }

    public function setCredit($credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getDoingAt(): ?\DateTimeInterface
    {
        return $this->doingAt;
    }

    public function setDoingAt(\DateTimeInterface $doingAt): self
    {
        $this->doingAt = $doingAt;

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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getJournal(): ?Journal
    {
        return $this->journal;
    }

    public function setJournal(?Journal $journal): self
    {
        $this->journal = $journal;

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

    public function getTransactionNumber(): ?string
    {
        return $this->transactionNumber;
    }

    public function setTransactionNumber(string $transactionNumber): self
    {
        $this->transactionNumber = $transactionNumber;

        return $this;
    }

    public function getWriteAt(): ?\DateTimeInterface
    {
        return $this->writeAt;
    }

    public function setWriteAt(?\DateTimeInterface $writeAt): self
    {
        $this->writeAt = $writeAt;

        return $this;
    }

    public function getIsSent(): ?bool
    {
        return $this->is_sent;
    }

    public function setIsSent(?bool $is_sent): self
    {
        $this->is_sent = $is_sent;

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
