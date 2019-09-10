<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\Table(name="act_account")
 * @UniqueEntity("number",
 *     message="This number already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Account
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Account's number can not be blank"
     * )
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *     message = "Account's title can not be blank"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_lock;

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
     * @ORM\Column(type="boolean")
     */
    private $is_final;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titleFr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $class;

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getNumber(): ? string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getTitle(): ? string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIsLock(): ? bool
    {
        return $this->is_lock;
    }

    public function setIsLock(bool $is_lock): self
    {
        $this->is_lock = $is_lock;

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
        if ($this->title) {
            return $this->number . " - " . $this->title;
        } else {
            return $this->number;
        }
    }

    public function getIsFinal(): ? bool
    {
        return $this->is_final;
    }

    public function setIsFinal(bool $is_final): self
    {
        $this->is_final = $is_final;

        return $this;
    }

    public function getTitleFr(): ? string
    {
        return $this->titleFr;
    }

    public function setTitleFr(? string $titleFr): self
    {
        $this->titleFr = $titleFr;

        return $this;
    }

    public function getClass(): ?int
    {
        return $this->class;
    }

    public function setClass(?int $class): self
    {
        $this->class = $class;

        return $this;
    }
}
