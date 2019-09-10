<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BankRepository")
 * @ORM\Table(name="tre_bank")
 * @UniqueEntity("code",
 *     message="This code already exist."
 * )
 * @UniqueEntity("name",
 *     message="This name already exist."
 * )
 * @UniqueEntity("account_number",
 *     message="This bank account already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Bank
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Bank's code can not be blank"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Bank's name can not be blank"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $fixphone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $mobilephone;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $pobox;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $taxpayernumber;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $businessnumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_swift;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_ibam;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_bank;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_branch;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Assert\NotBlank(
     *     message = "Bank's account can not be blank"
     * )
     */
    private $account_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_rib;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $balance;

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

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getCode(): ? string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ? string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ? string
    {
        return $this->location;
    }

    public function setLocation(? string $location): self
    {
        $this->location = $location;

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

    public function getUser(): ? User
    {
        return $this->user;
    }

    public function setUser(? User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEmail(): ? string
    {
        return $this->email;
    }

    public function setEmail(? string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFixphone(): ? string
    {
        return $this->fixphone;
    }

    public function setFixphone(? string $fixphone): self
    {
        $this->fixphone = $fixphone;

        return $this;
    }

    public function getMobilephone(): ? string
    {
        return $this->mobilephone;
    }

    public function setMobilephone(? string $mobilephone): self
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }

    public function getPobox(): ? string
    {
        return $this->pobox;
    }

    public function setPobox(? string $pobox): self
    {
        $this->pobox = $pobox;

        return $this;
    }

    public function getTaxpayernumber(): ? string
    {
        return $this->taxpayernumber;
    }

    public function setTaxpayernumber(? string $taxpayernumber): self
    {
        $this->taxpayernumber = $taxpayernumber;

        return $this;
    }

    public function getBusinessnumber(): ? string
    {
        return $this->businessnumber;
    }

    public function setBusinessnumber(? string $businessnumber): self
    {
        $this->businessnumber = $businessnumber;

        return $this;
    }

    public function getCodeSwift(): ? string
    {
        return $this->code_swift;
    }

    public function setCodeSwift(? string $code_swift): self
    {
        $this->code_swift = $code_swift;

        return $this;
    }

    public function getCodeIbam(): ? string
    {
        return $this->code_ibam;
    }

    public function setCodeIbam(? string $code_ibam): self
    {
        $this->code_ibam = $code_ibam;

        return $this;
    }

    public function getCodeBank(): ? string
    {
        return $this->code_bank;
    }

    public function setCodeBank(? string $code_bank): self
    {
        $this->code_bank = $code_bank;

        return $this;
    }

    public function getCodeBranch(): ? string
    {
        return $this->code_branch;
    }

    public function setCodeBranch(? string $code_branch): self
    {
        $this->code_branch = $code_branch;

        return $this;
    }

    public function getAccountNumber(): ? string
    {
        return $this->account_number;
    }

    public function setAccountNumber(? string $account_number): self
    {
        $this->account_number = $account_number;

        return $this;
    }

    public function getCodeRib(): ? string
    {
        return $this->code_rib;
    }

    public function setCodeRib(? string $code_rib): self
    {
        $this->code_rib = $code_rib;

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

    public function getAccount(): ? Account
    {
        return $this->account;
    }

    public function setAccount(? Account $account): self
    {
        $this->account = $account;

        return $this;
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

    /**
     * Generates the magic method
     */
    public function __toString()
    {
        return $this->name;
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
}
