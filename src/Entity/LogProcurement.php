<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Twig_Sandbox_SecurityNotAllowedTagError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogProcurementRepository")
 * @ORM\Table(name="tra_log_procurement")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class LogProcurement
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
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $consignment_note;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $volume;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Park")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destination;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $byingAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Wood")
     */
    private $wood;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\Column(type="float")
     */
    private $quantityToDelever;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getConsignmentNote(): ?string
    {
        return $this->consignment_note;
    }

    public function setConsignmentNote(string $consignment_note): self
    {
        $this->consignment_note = $consignment_note;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): self
    {
        $this->volume = $volume;

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

    public function getDestination(): ?Park
    {
        return $this->destination;
    }

    public function setDestination(?Park $destination): self
    {
        $this->destination = $destination;

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

    public function getByingAt(): ?\DateTimeInterface
    {
        return $this->byingAt;
    }

    public function setByingAt(?\DateTimeInterface $byingAt): self
    {
        $this->byingAt = $byingAt;

        return $this;
    }

    public function getWood(): ?Wood
    {
        return $this->wood;
    }

    public function setWood(?Wood $wood): self
    {
        $this->wood = $wood;

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

    /**
     * Generates the magic method
     *
     */
    public function __toString()
    {
        // to show the name of the Role in the select
        return $this->reference;
    }

    public function getQuantityToDelever(): ?float
    {
        return $this->quantityToDelever;
    }

    public function setQuantityToDelever(float $quantityToDelever): self
    {
        $this->quantityToDelever = $quantityToDelever;

        return $this;
    }
}
