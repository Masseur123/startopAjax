<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupplyRequestArticleRepository")
 * @ORM\Table(name="com_supply_request_article")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class SupplyRequestArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SupplyRequest")
     * @ORM\JoinColumn(nullable=false)
     */
    private $supply;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupply(): ?SupplyRequest
    {
        return $this->supply;
    }

    public function setSupply(?SupplyRequest $supply): self
    {
        $this->supply = $supply;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $is_valid): self
    {
        $this->isValid = $is_valid;

        return $this;
    }

    /**
     * Generates the magic method
     *
     */
    public function __toString(){
        // to show     the name of the Role in the select
        return $this->getSupply()->getReference();
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
}

   