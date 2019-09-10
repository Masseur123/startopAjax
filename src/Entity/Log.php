<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Twig_Node;
use Twig_Token;
use Twig_TokenParser_Flush;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 * @ORM\Table(name="tra_log")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Log
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gb;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pb;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dm;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $volume;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Wood")
     */
    private $wood;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LogRouting", mappedBy="logreferences")
     */
    private $logRoutings;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LogProcurement", inversedBy="logs")
	 * @ORM\JoinColumn(nullable=true)
     */
    private $logProcurement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $internum;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transit")
     */
    private $transit;

    public function __construct()
    {
        $this->logRoutings = new ArrayCollection();
    }

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

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getGb(): ?int
    {
        return $this->gb;
    }

    public function setGb(?int $gb): self
    {
        $this->gb = $gb;

        return $this;
    }

    public function getPb(): ?int
    {
        return $this->pb;
    }

    public function setPb(?int $pb): self
    {
        $this->pb = $pb;

        return $this;
    }

    public function getDm(): ?int
    {
        return $this->dm;
    }

    public function setDm(?int $dm): self
    {
        $this->dm = $dm;

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

    /**
     * @return Collection|LogRouting[]
     */
    public function getLogRoutings(): Collection
    {
        return $this->logRoutings;
    }

    public function addLogRouting(LogRouting $logRouting): self
    {
        if (!$this->logRoutings->contains($logRouting)) {
            $this->logRoutings[] = $logRouting;
            $logRouting->addLogreference($this);
        }

        return $this;
    }

    public function removeLogRouting(LogRouting $logRouting): self
    {
        if ($this->logRoutings->contains($logRouting)) {
            $this->logRoutings->removeElement($logRouting);
            $logRouting->removeLogreference($this);
        }

        return $this;
    }

    /**
     * Generates the magic method
     */
    public function __toString()
    {
        if ($this->reference) {
            return $this->reference;
        } else {
            return "";
        }
    }

    public function getLogProcurement(): ?LogProcurement
    {
        return $this->logProcurement;
    }

    public function setLogProcurement(?LogProcurement $logProcurement): self
    {
        $this->logProcurement = $logProcurement;

        return $this;
    }

    public function getInternum(): ?string
    {
        return $this->internum;
    }

    public function setInternum(?string $internum): self
    {
        $this->internum = $internum;

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
}
