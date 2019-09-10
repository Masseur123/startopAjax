<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Twig_Filter;
use Twig_Node;
use Twig_Sandbox_SecurityNotAllowedTagError;
use Twig_TokenParser_Flush;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StockWoodRepository")
 * @ORM\Table(name="tra_stock_wood")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class StockWood
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Wood", inversedBy="stockWoods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wood;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $volume;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stockAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_add;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LogProcurement")
     */
    private $procurement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LoadingLog")
     */
    private $loading;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     * @ORM\JoinColumn(nullable=false)
     */
    private $branch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Park")
     */
    private $park;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getStockAt(): ?\DateTimeInterface
    {
        return $this->stockAt;
    }

    public function setStockAt(\DateTimeInterface $stockAt): self
    {
        $this->stockAt = $stockAt;

        return $this;
    }

    public function getIsAdd(): ?bool
    {
        return $this->is_add;
    }

    public function setIsAdd(bool $is_add): self
    {
        $this->is_add = $is_add;

        return $this;
    }

    public function getProcurement(): ?LogProcurement
    {
        return $this->procurement;
    }

    public function setProcurement(?LogProcurement $procurement): self
    {
        $this->procurement = $procurement;

        return $this;
    }

    public function getLoading(): ?LoadingLog
    {
        return $this->loading;
    }

    public function setLoading(?LoadingLog $loading): self
    {
        $this->loading = $loading;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getPark(): ?Park
    {
        return $this->park;
    }

    public function setPark(?Park $park): self
    {
        $this->park = $park;

        return $this;
    }
}
