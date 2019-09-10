<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Tests\Extension\Core\EventListener\MergeCollectionListenerArrayObjectTest;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuotationArticleRepository")
 * @ORM\Table(name="com_quotation_article")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class QuotationArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Quotation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quotation;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getQuotation(): ?Quotation
    {
        return $this->quotation;
    }

    public function setQuotation(?Quotation $quotation): self
    {
        $this->quotation = $quotation;

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

    /**
     * Generates the magic method
     *
     */
    public function __toString(){
        // to show     the name of the Role in the select
        return $this->getQuotation()->getReference();
    }

    public function getPu(): ?int
    {
        return $this->pu;
    }

    public function setPu(?int $pu): self
    {
        $this->pu = $pu;

        return $this;
    }

    public function getPt(): ?int
    {
        return $this->pt;
    }

    public function setPt(?int $pt): self
    {
        $this->pt = $pt;

        return $this;
    }
}
