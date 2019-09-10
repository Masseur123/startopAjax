<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeleveryRepository")
 * @ORM\Table(name="com_delevery")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Delevery
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
     * @ORM\JoinColumn(nullable=true)
     */
    private $provider;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Order", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
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

    public function getCommande(): ?Order
    {
        return $this->commande;
    }

    public function setCommande(Order $commande): self
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
