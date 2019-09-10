<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Twig_Sandbox_SecurityNotAllowedFunctionError;
use Twig_Sandbox_SecurityNotAllowedTagError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogRoutingRepository")
 * @ORM\Table(name="tra_log_routing")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class LogRouting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $consignment_note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Park")
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Port")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destination;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $routingAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Log", inversedBy="logRoutings")
     * @ORM\JoinTable(name="tra_log_log_routing")
     */
    private $logreferences;

    public function __construct()
    {
        $this->logreferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSource(): ?Park
    {
        return $this->source;
    }

    public function setSource(?Park $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDestination(): ?Port
    {
        return $this->destination;
    }

    public function setDestination(?Port $destination): self
    {
        $this->destination = $destination;

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

    public function getRoutingAt(): ?\DateTimeInterface
    {
        return $this->routingAt;
    }

    public function setRoutingAt(?\DateTimeInterface $routingAt): self
    {
        $this->routingAt = $routingAt;

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
     * @return Collection|Log[]
     */
    public function getLogreferences(): Collection
    {
        return $this->logreferences;
    }

    public function addLogreference(Log $logreference): self
    {
        if (!$this->logreferences->contains($logreference)) {
            $this->logreferences[] = $logreference;
        }

        return $this;
    }

    public function removeLogreference(Log $logreference): self
    {
        if ($this->logreferences->contains($logreference)) {
            $this->logreferences->removeElement($logreference);
        }

        return $this;
    }
}
