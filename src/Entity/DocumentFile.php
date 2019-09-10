<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Twig_NodeVisitor_SafeAnalysis;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentFileRepository")
 * @ORM\Table(name="tra_document_file")
 *  @UniqueEntity("code",
 *     message ="This code already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 *
 */
class DocumentFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(
     *     message = "Document's code can not be not blank"
     * )
     */
    private $code;

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
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TransitHist", mappedBy="document")
     */
    private $transitHists;

    public function __construct()
    {
        $this->transitHists = new ArrayCollection();
    }

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getCode(): ? string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
        // to show the code of the document in the select
        return $this->code;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection|TransitHist[]
     */
    public function getTransitHists(): Collection
    {
        return $this->transitHists;
    }

    public function addTransitHist(TransitHist $transitHist): self
    {
        if (!$this->transitHists->contains($transitHist)) {
            $this->transitHists[] = $transitHist;
            $transitHist->setDocument($this);
        }

        return $this;
    }

    public function removeTransitHist(TransitHist $transitHist): self
    {
        if ($this->transitHists->contains($transitHist)) {
            $this->transitHists->removeElement($transitHist);
            // set the owning side to null (unless already changed)
            if ($transitHist->getDocument() === $this) {
                $transitHist->setDocument(null);
            }
        }

        return $this;
    }
}
