<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Twig_Extension_Debug;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\Table(name="in_person")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $bornAt;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $fixphone;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $mobilephone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $passport;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $idcardnumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $job;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $pobox;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $website;

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
    private $logo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cigle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Civility")
     */
    private $civility;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BusinessType")
     */
    private $businessType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePerson")
     */
    private $type;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBornAt(): ?\DateTimeInterface
    {
        return $this->bornAt;
    }

    public function setBornAt(?\DateTimeInterface $bornAt): self
    {
        $this->bornAt = $bornAt;

        return $this;
    }

    public function getFixphone(): ?string
    {
        return $this->fixphone;
    }

    public function setFixphone(?string $fixphone): self
    {
        $this->fixphone = $fixphone;

        return $this;
    }

    public function getMobilephone(): ?string
    {
        return $this->mobilephone;
    }

    public function setMobilephone(?string $mobilephone): self
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }

    public function getPassport(): ?string
    {
        return $this->passport;
    }

    public function setPassport(?string $passport): self
    {
        $this->passport = $passport;

        return $this;
    }

    public function getIdcardnumber(): ?string
    {
        return $this->idcardnumber;
    }

    public function setIdcardnumber(?string $idcardnumber): self
    {
        $this->idcardnumber = $idcardnumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

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

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function setCivility(?Civility $civility): self
    {
        $this->civility = $civility;

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

    public function getPobox(): ?string
    {
        return $this->pobox;
    }

    public function setPobox(?string $pobox): self
    {
        $this->pobox = $pobox;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getTaxpayernumber(): ?string
    {
        return $this->taxpayernumber;
    }

    public function setTaxpayernumber(?string $taxpayernumber): self
    {
        $this->taxpayernumber = $taxpayernumber;

        return $this;
    }

    public function getBusinessnumber(): ?string
    {
        return $this->businessnumber;
    }

    public function setBusinessnumber(?string $businessnumber): self
    {
        $this->businessnumber = $businessnumber;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCigle(): ?string
    {
        return $this->cigle;
    }

    public function setCigle(?string $cigle): self
    {
        $this->cigle = $cigle;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBusinessType(): ?BusinessType
    {
        return $this->businessType;
    }

    public function setBusinessType(?BusinessType $businessType): self
    {
        $this->businessType = $businessType;

        return $this;
    }

    public function getType(): ?TypePerson
    {
        return $this->type;
    }

    public function setType(?TypePerson $type): self
    {
        $this->type = $type;

        return $this;
    }
}