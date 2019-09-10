<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SalesOrderArticleRepository")
 * @ORM\Table(name="sales_order_article")
 */
class SalesOrderArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isvalid;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pu;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SalesOrder")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

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


    public function getIsvalid(): ?bool
    {
        return $this->isvalid;
    }

    public function setIsvalid(?bool $isvalid): self
    {
        $this->isvalid = $isvalid;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

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

    public function getCommande(): ?SalesOrder
    {
        return $this->commande;
    }

    public function setCommande(?SalesOrder $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
