<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Tests\DependencyInjection\RoutingResolverPassTest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Twig_TokenStream;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserGroupRepository")
 * @ORM\Table(name="se_user_group")
 * 
 * @UniqueEntity("name",
 *     message="This group already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class UserGroup
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
     *     message = "Group's name can not be blank"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_enabled;

    /**
     * Many UserGroup have Many Components
     * @ORM\ManyToMany(targetEntity="App\Entity\Component", cascade={"persist"})
     * @ORM\JoinTable(name="se_user_group_component")
     */
    private $components;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch", inversedBy="userGroups")
     */
    private $branch;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupComponentRole", mappedBy="usergroup")
     */
    private $groupComponentRoles;

    public function __construct()
    {
        $this->components = new ArrayCollection();
        $this->groupComponentRoles = new ArrayCollection();
    }

    public function getId()
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

    public function getCreatedAt(): ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsEnabled(): ? bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->contains($component)) {
            $this->components->removeElement($component);
        }

        return $this;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
	
	public function __toString()
    {
        // to show the name of the UserGroup in the select
        return $this->name;
    }

    /**
     * @return Collection|GroupComponentRole[]
     */
    public function getGroupComponentRoles(): Collection
    {
        return $this->groupComponentRoles;
    }

    public function addGroupComponentRole(GroupComponentRole $groupComponentRole): self
    {
        if (!$this->groupComponentRoles->contains($groupComponentRole)) {
            $this->groupComponentRoles[] = $groupComponentRole;
            $groupComponentRole->setUsergroup($this);
        }

        return $this;
    }

    public function removeGroupComponentRole(GroupComponentRole $groupComponentRole): self
    {
        if ($this->groupComponentRoles->contains($groupComponentRole)) {
            $this->groupComponentRoles->removeElement($groupComponentRole);
            // set the owning side to null (unless already changed)
            if ($groupComponentRole->getUsergroup() === $this) {
                $groupComponentRole->setUsergroup(null);
            }
        }

        return $this;
    }
}
