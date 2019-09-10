<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParkRepository")
 * @ORM\Table(name="tra_park")
 * @UniqueEntity("name",
 *     message="This park already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 *
 */
class Park
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
     *     message = "Park's name can not be blank"
     * )
     * @Assert\Length(
     *     min = 5,
     *     max = 255,
     *     minMessage = "Park's name must be at least {{ limit }} characters long",
     *     maxMessage = "Park's name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Park's address can not be blank"
     * )
     * @Assert\Length(
     *     min = 5,
     *     max = 255,
     *     minMessage = "Park's address must be at least {{ limit }} characters long",
     *     maxMessage = "Park's address cannot be longer than {{ limit }} characters"
     * )
     */
    private $address;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    public function getId(): ? int
    {
        return $this->id;
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

    public function getAddress(): ? string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getBranch(): ? Branch
    {
        return $this->branch;
    }

    public function setBranch(? Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }
}
