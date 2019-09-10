<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Http\Firewall;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Twig_Node;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoadingRepository")
 * @ORM\Table(name="tra_loading")
 *
 * @UniqueEntity("reference",
 *     message="This reference already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Loading
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, unique=true)
     * @Assert\Length(min=6, max=20)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Container")
     */
    private $container;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $loadingAt;

    /**
     * @ORM\Column(type="integer", nullable=false)
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
     */
    private $branch;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getContainer() : ? Container
    {
        return $this->container;
    }

    public function setContainer(? Container $container) : self
    {
        $this->container = $container;

        return $this;
    }

    public function getReference() : ? string
    {
        return $this->reference;
    }

    public function setReference(? string $reference) : self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getLoadingAt() : ? \DateTimeInterface
    {
        return $this->loadingAt;
    }

    public function setLoadingAt(? \DateTimeInterface $loadingAt) : self
    {
        $this->loadingAt = $loadingAt;

        return $this;
    }

    public function getNbrofpiece() : ? int
    {
        return $this->nbrofpiece;
    }

    public function setNbrofpiece(? int $nbrofpiece) : self
    {
        $this->nbrofpiece = $nbrofpiece;

        return $this;
    }

    public function getVolume() : ? float
    {
        return $this->volume;
    }

    public function setVolume(? float $volume) : self
    {
        $this->volume = $volume;

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
}
