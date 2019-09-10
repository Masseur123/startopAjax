<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeleveryArticleRepository")
 * @ORM\Table(name="con_delevery_article")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class DeleveryArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delevery")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delevery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pu;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Stock")
     */
    private $stockage;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDelevery(): ?Delevery
    {
        return $this->delevery;
    }

    public function setDelevery(?Delevery $delevery): self
    {
        $this->delevery = $delevery;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getPu(): ?float
    {
        return $this->pu;
    }

    public function setPu(?float $pu): self
    {
        $this->pu = $pu;

        return $this;
    }

    public function getPt(): ?float
    {
        return $this->pt;
    }

    public function setPt(?float $pt): self
    {
        $this->pt = $pt;

        return $this;
    }

    public function getStockage(): ?Stock
    {
        return $this->stockage;
    }

    public function setStockage(?Stock $stockage): self
    {
        $this->stockage = $stockage;

        return $this;
    }
}
