<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BranchRepository")
 * @ORM\Table(name="se_branch")
 * @UniqueEntity("code",
 *     message="This code already exist."
 * )
 * 
 * @UniqueEntity("name",
 *     message="This name already exist."
 * )
 * 
 * @UniqueEntity("mobilephone",
 *     message="This mobilephone already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Branch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=21, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Branch's code can not be blank"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Branch's name can not be blank"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $fixphone;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Branch's phone can not be blank"
     * )
     */
    private $mobilephone;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $town;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Institution")
     * @ORM\JoinColumn(nullable=false)
     */
    private $institution;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserGroup", mappedBy="branch")
     */
    private $userGroups;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
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

    public function getName(): ? string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ? string
    {
        return $this->location;
    }

    public function setLocation(? string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getFixphone(): ? string
    {
        return $this->fixphone;
    }

    public function setFixphone(? string $fixphone): self
    {
        $this->fixphone = $fixphone;

        return $this;
    }

    public function getMobilephone(): ? string
    {
        return $this->mobilephone;
    }

    public function setMobilephone(? string $mobilephone): self
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }

    public function getEmail(): ? string
    {
        return $this->email;
    }

    public function setEmail(? string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTown(): ? string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

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

    public function getInstitution(): ? Institution
    {
        return $this->institution;
    }

    public function setInstitution(? Institution $institution): self
    {
        $this->institution = $institution;

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

    /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->setBranch($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->contains($userGroup)) {
            $this->userGroups->removeElement($userGroup);
            // set the owning side to null (unless already changed)
            if ($userGroup->getBranch() === $this) {
                $userGroup->setBranch(null);
            }
        }

        return $this;
    }
}
