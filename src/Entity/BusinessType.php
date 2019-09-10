<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusinessTypeRepository")
 * @ORM\Table(name="co_businessType")
 *
 * @UniqueEntity("code",
 *     message="This code already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class BusinessType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=12, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=6, max=12)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Blank
     * @Assert\Length(min=10)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getCode() : ? string
    {
        return $this->code;
    }

    public function setCode(string $code) : self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription() : ? string
    {
        return $this->description;
    }

    public function setDescription(? string $description) : self
    {
        $this->description = $description;

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

    public function getUser() : ? User
    {
        return $this->user;
    }

    public function setUser(? User $user) : self
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
        // to show the fullname of the User in the select
        return $this->code;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
