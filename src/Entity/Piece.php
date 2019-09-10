<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Twig_Sandbox_SecurityNotAllowedPropertyError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PieceRepository")
 * @ORM\Table(name="act_piece")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Piece
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Journal")
     */
    private $journal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     */
    private $accountDb;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     */
    private $accountCr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pieceNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Year")
     * @ORM\JoinColumn(nullable=false)
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\Column(type="datetime")
     */
    private $doingAt;

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
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $debit;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     */
    private $credit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumn(nullable=true)
     */
    private $account;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getReference() : ? string
    {
        return $this->reference;
    }

    public function setReference(string $reference) : self
    {
        $this->reference = $reference;

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

    public function getJournal() : ? Journal
    {
        return $this->journal;
    }

    public function setJournal(? Journal $journal) : self
    {
        $this->journal = $journal;

        return $this;
    }

    public function getAccountDb() : ? Account
    {
        return $this->accountDb;
    }

    public function setAccountDb(? Account $accountDb) : self
    {
        $this->accountDb = $accountDb;

        return $this;
    }

    public function getAccountCr() : ? Account
    {
        return $this->accountCr;
    }

    public function setAccountCr(? Account $accountCr) : self
    {
        $this->accountCr = $accountCr;

        return $this;
    }

    public function getPieceNumber() : ? string
    {
        return $this->pieceNumber;
    }

    public function setPieceNumber(string $pieceNumber) : self
    {
        $this->pieceNumber = $pieceNumber;

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

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount) : self
    {
        $this->amount = $amount;

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

    public function getBranch() : ? Branch
    {
        return $this->branch;
    }

    public function setBranch(? Branch $branch) : self
    {
        $this->branch = $branch;

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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
