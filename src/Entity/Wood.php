<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WoodRepository")
 * @ORM\Table(name="tra_wood")
 * @UniqueEntity("code",
 *     message="This code already exist."
 * )
 * @UniqueEntity("name",
 *     message="This name already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 *
 */
class Wood
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Assert\NotBlank(
     *     message = "Wood's code can not be blank"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(
     *     message = "Wood's name can not be blank"
     * )
     */
    private $name;

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
     * @ORM\OneToMany(targetEntity="App\Entity\StockWood", mappedBy="wood")
     */
    private $stockWoods;

    public function __construct()
    {
        $this->stockWoods = new ArrayCollection();
    }

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getCode(): ? string
    {
        return $this->code;
    }

    public function setCode(? string $code): self
    {
        $this->code = $code;

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

    public function getCreatedAt(): ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

        return $this->name;
    }

    /**
     * @return Collection|StockWood[]
     */
    public function getStockWoods(): Collection
    {
        return $this->stockWoods;
    }

    public function addStockWood(StockWood $stockWood): self
    {
        if (!$this->stockWoods->contains($stockWood)) {
            $this->stockWoods[] = $stockWood;
            $stockWood->setWood($this);
        }

        return $this;
    }

    public function removeStockWood(StockWood $stockWood): self
    {
        if ($this->stockWoods->contains($stockWood)) {
            $this->stockWoods->removeElement($stockWood);
            // set the owning side to null (unless already changed)
            if ($stockWood->getWood() === $this) {
                $stockWood->setWood(null);
            }
        }

        return $this;
    }
}
