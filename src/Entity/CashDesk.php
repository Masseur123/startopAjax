<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CashDeskRepository")
 * @ORM\Table(name="tre_cash_desk")
 * @UniqueEntity("code",
 *     message="Already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class CashDesk
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Cash Desk's code can not be blank"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_open;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $balance;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Journal")
     */
    private $journal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $operator;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_main;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $debit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $credit;

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getCode(): ? string
    {
        return $this->code;
    }

    public function setCode(? string $code): self
    {
        $this->code = $code;

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

    public function getIsOpen(): ? bool
    {
        return $this->is_open;
    }

    public function setIsOpen(bool $is_open): self
    {
        $this->is_open = $is_open;

        return $this;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($balance): self
    {
        $this->balance = $balance;

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

    public function getAccount(): ? Account
    {
        return $this->account;
    }

    public function setAccount(? Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Generates the magic method
     *
     */
    public function __toString()
    {
        return $this->code;
    }

    public function getJournal(): ? Journal
    {
        return $this->journal;
    }

    public function setJournal(? Journal $journal): self
    {
        $this->journal = $journal;

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

    public function getOperator(): ? User
    {
        return $this->operator;
    }

    public function setOperator(? User $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getIsMain(): ? bool
    {
        return $this->is_main;
    }

    public function setIsMain(? bool $is_main): self
    {
        $this->is_main = $is_main;

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
}
