<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CashDeskSituationRepository")
 * @ORM\Table(name="tre_cash_desk_situation")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class CashDeskSituation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashDesk")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cashDesk;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $debit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $credit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $balance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $saveAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     * @ORM\JoinColumn(nullable=false)
     */
    private $branch;

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getCashDesk(): ? CashDesk
    {
        return $this->cashDesk;
    }

    public function setCashDesk(? CashDesk $cashDesk): self
    {
        $this->cashDesk = $cashDesk;

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

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getSaveAt(): ? \DateTimeInterface
    {
        return $this->saveAt;
    }

    public function setSaveAt(\DateTimeInterface $saveAt): self
    {
        $this->saveAt = $saveAt;

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
