<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

 /**
 * @ORM\Entity(repositoryClass="App\Repository\LoadingLogRepository")
 * @ORM\Table(name="tra_loading_log")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class LoadingLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Container")
     */
    private $container;

    /**
     * @ORM\Column(type="datetime")
     */
    private $loadAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrofpiece;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $volume;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Wood")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wood;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     * @ORM\JoinColumn(nullable=false)
     */
    private $branch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transit")
     */
    private $transit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="float")
     */
    private $quantityToDelever;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContainer(): ?Container
    {
        return $this->container;
    }

    public function setContainer(?Container $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function getLoadAt(): ?\DateTimeInterface
    {
        return $this->loadAt;
    }

    public function setLoadAt(\DateTimeInterface $loadAt): self
    {
        $this->loadAt = $loadAt;

        return $this;
    }

    public function getNbrofpiece(): ?int
    {
        return $this->nbrofpiece;
    }

    public function setNbrofpiece(int $nbrofpiece): self
    {
        $this->nbrofpiece = $nbrofpiece;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getTransit(): ?Transit
    {
        return $this->transit;
    }

    public function setTransit(?Transit $transit): self
    {
        $this->transit = $transit;

        return $this;
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
