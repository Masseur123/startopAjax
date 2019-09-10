<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Security\Http\Firewall;
use Twig_Sandbox_SecurityNotAllowedTagError;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContainerTrackingRepository")
 * @ORM\Table(name="tra_container_tracking")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class ContainerTracking
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
    private $destination;

    /**
     * @ORM\Column(type="datetime")
     */
    private $trackAt;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Park")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sources;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Container", inversedBy="containerTrackings")
     * @ORM\JoinTable(name="tra_container_container_tracking")
     */
    private $containers;

    public function __construct()
    {
        $this->containers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getTrackAt(): ?\DateTimeInterface
    {
        return $this->trackAt;
    }

    public function setTrackAt(\DateTimeInterface $trackAt): self
    {
        $this->trackAt = $trackAt;

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

    public function getSources(): ?Park
    {
        return $this->sources;
    }

    public function setSources(?Park $sources): self
    {
        $this->sources = $sources;

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
     * @return Collection|Container[]
     */
    public function getContainers(): Collection
    {
        return $this->containers;
    }

    public function addContainer(Container $container): self
    {
        if (!$this->containers->contains($container)) {
            $this->containers[] = $container;
        }

        return $this;
    }

    public function removeContainer(Container $container): self
    {
        if ($this->containers->contains($container)) {
            $this->containers->removeElement($container);
        }

        return $this;
    }
}
