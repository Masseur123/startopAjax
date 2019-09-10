<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalesDeleveryRepository")
 * @ORM\Table(name="sales_delevery")
 */
class SalesDelevery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $reference;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleverAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider")
     */
    private $provider;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SalesOrder", cascade={"persist", "remove"})
     */
    private $commande;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isvalid;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeleverAt(): ?\DateTimeInterface
    {
        return $this->deleverAt;
    }

    public function setDeleverAt(?\DateTimeInterface $deleverAt): self
    {
        $this->deleverAt = $deleverAt;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getCommande(): ?SalesOrder
    {
        return $this->commande;
    }

    public function setCommande(?SalesOrder $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getIsvalid(): ?bool
    {
        return $this->isvalid;
    }

    public function setIsvalid(?bool $isvalid): self
    {
        $this->isvalid = $isvalid;

        return $this;
    }
}
