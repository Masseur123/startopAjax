<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(name="ar_article")
 *
 * @UniqueEntity("reference",
 *     message="This reference already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=50)
     */
    private $reference;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Itemtype")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Unity")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank()
     */
    private $unity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Family")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank()
     */
    private $family;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6)
     */
    private $pua;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6)
     */
    private $puv;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $puvmin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $puvmax;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_storable;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dimensions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $symbol;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockAlert;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6)
     */
    private $reserv;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_lost;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $lostAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6)
     */
    private $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6)
     */
    private $account_var;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6)
     */
    private $account_pur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6)
     */
    private $account_sale;


    public function getId() : ? int
    {
        return $this->id;
    }

    public function getReference() : ? string
    {
        return $this->reference;
    }

    public function setReference(string $reference) : self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getName() : ? string
    {
        return $this->name;
    }

    public function setName(? string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt() : ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getType() : ? Itemtype
    {
        return $this->type;
    }

    public function setType(? Itemtype $type) : self
    {
        $this->type = $type;

        return $this;
    }

    public function getUnity() : ? Unity
    {
        return $this->unity;
    }

    public function setUnity(? Unity $unity) : self
    {
        $this->unity = $unity;

        return $this;
    }

    public function getFamily() : ? Family
    {
        return $this->family;
    }

    public function setFamily(? Family $family) : self
    {
        $this->family = $family;

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


    public function getPua() : ? string
    {
        return $this->pua;
    }

    public function setPua(string $pua) : self
    {
        $this->pua = $pua;

        return $this;
    }

    public function getPuv() : ? string
    {
        return $this->puv;
    }

    public function setPuv(string $puv) : self
    {
        $this->puv = $puv;

        return $this;
    }

    public function getPuvmin() : ? float
    {
        return $this->puvmin;
    }

    public function setPuvmin(? float $puvmin) : self
    {
        $this->puvmin = $puvmin;

        return $this;
    }

    public function getPuvmax() : ? float
    {
        return $this->puvmax;
    }

    public function setPuvmax(? float $puvmax) : self
    {
        $this->puvmax = $puvmax;

        return $this;
    }

    public function getIsStorable() : ? bool
    {
        return $this->is_storable;
    }

    public function setIsStorable(bool $is_storable) : self
    {
        $this->is_storable = $is_storable;

        return $this;
    }

    public function getWeight() : ? int
    {
        return $this->weight;
    }

    public function setWeight(int $weight) : self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getDimensions() : ? string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions) : self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getSymbol() : ? string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol) : self
    {
        $this->symbol = $symbol;

        return $this;
    }



    public function getStockAlert() : ? string
    {
        return $this->stockAlert;
    }

    public function setStockAlert(string $stockAlert) : self
    {
        $this->stockAlert = $stockAlert;

        return $this;
    }

    public function getReserv() : ? string
    {
        return $this->reserv;
    }

    public function setReserv(string $reserv) : self
    {
        $this->reserv = $reserv;

        return $this;
    }

    public function getIsLost() : ? bool
    {
        return $this->is_lost;
    }

    public function setIsLost(? bool $is_lost) : self
    {
        $this->is_lost = $is_lost;

        return $this;
    }

    public function getLostAt() : ? \DateTimeInterface
    {
        return $this->lostAt;
    }

    public function setLostAt(? \DateTimeInterface $lostAt) : self
    {
        $this->lostAt = $lostAt;

        return $this;
    }

    public function getAccount() : ? string
    {
        return $this->account;
    }

    public function setAccount(string $account) : self
    {
        $this->account = $account;

        return $this;
    }

    public function getAccountVar() : ? string
    {
        return $this->account_var;
    }

    public function setAccountVar(string $account_var) : self
    {
        $this->account_var = $account_var;

        return $this;
    }

    public function getAccountPur() : ? string
    {
        return $this->account_pur;
    }

    public function setAccountPur(string $account_pur) : self
    {
        $this->account_pur = $account_pur;

        return $this;
    }

    public function getAccountSale() : ? string
    {
        return $this->account_sale;
    }

    public function setAccountSale(string $account_sale) : self
    {
        $this->account_sale = $account_sale;

        return $this;
    }

    /**
     * Generates the magic method
     *
     */
    public function __toString()
    {
        // to show the name of the article in the select
        if (empty($this->getName())) {
            return $this->getReference();
        } else {
            return $this->getReference() . " - " . $this->getName();
        }
    }
}
