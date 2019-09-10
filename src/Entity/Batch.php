<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Twig_TokenParser_Include;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BatchRepository")
 * @ORM\Table(name="tra_batch")
 *
 * @UniqueEntity("reference",
 *     message="This reference already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Batch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=20)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transit")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $transit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ShippingLine")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $shippingline;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Containerlength")
     * @Assert\NotBlank()
     */
    private $containerlength;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Batch")
     */
    private $branch;

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

    public function getCreatedAt() : ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNumber() : ? int
    {
        return $this->number;
    }

    public function setNumber(int $number) : self
    {
        $this->number = $number;

        return $this;
    }

    public function getShippingline() : ? ShippingLine
    {
        return $this->shippingline;
    }

    public function setShippingline(? ShippingLine $shippingline) : self
    {
        $this->shippingline = $shippingline;

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

    public function getTransit() : ? Transit
    {
        return $this->transit;
    }

    public function setTransit(? Transit $transit) : self
    {
        $this->transit = $transit;

        return $this;
    }

    /**
     * Generates the magic method
     *
     */
    public function __toString()
    {
        // to show the reference of the Role in the select
        return $this->reference;
    }

    public function getContainerlength() : ? Containerlength
    {
        return $this->containerlength;
    }

    public function setContainerlength(? Containerlength $containerlength) : self
    {
        $this->containerlength = $containerlength;

        return $this;
    }

    public function getBranch(): ?self
    {
        return $this->branch;
    }

    public function setBranch(?self $branch): self
    {
        $this->branch = $branch;

        return $this;
    }
}
