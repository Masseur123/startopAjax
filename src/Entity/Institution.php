<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstitutionRepository")
 * @ORM\Table(name="se_institution")
 * 
 * @UniqueEntity("name",
 *     message="This name already exist."
 * )
 * 
 * @UniqueEntity("mobilephone",
 *     message="This mobilephone already exist."
 * )
 * 
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Institution
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cigle;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Institution's name can not be blank"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $fixphone;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Institution's phone can not be blank"
     * )
     */
    private $mobilephone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $town;

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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BusinessType")
     */
    private $businessType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getCigle(): ? string
    {
        return $this->cigle;
    }

    public function setCigle(? string $cigle): self
    {
        $this->cigle = $cigle;

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

    public function getAddress(): ? string
    {
        return $this->address;
    }

    public function setAddress(? string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTown(): ? string
    {
        return $this->town;
    }

    public function setTown(? string $town): self
    {
        $this->town = $town;

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

    public function getWebsite(): ? string
    {
        return $this->website;
    }

    public function setWebsite(? string $website): self
    {
        $this->website = $website;

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

    public function getCreatedAt(): ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBusinessType(): ? BusinessType
    {
        return $this->businessType;
    }

    public function setBusinessType(? BusinessType $businessType): self
    {
        $this->businessType = $businessType;

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

    /**
     * Generates the magic method
     * 
     */
    public function __toString()
    {
        // to show the cigle
        return $this->cigle;
    }
}
