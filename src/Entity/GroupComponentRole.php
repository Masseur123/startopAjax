<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupComponentRoleRepository")
 * @ORM\Table(name="se_groupcomponentrole")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class GroupComponentRole
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserGroup", inversedBy="groupComponentRoles")
     */
    private $usergroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Component", inversedBy="groupComponentRoles")
     */
    private $component;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="groupComponentRoles")
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branch")
     */
    private $branch;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsergroup(): ?UserGroup
    {
        return $this->usergroup;
    }

    public function setUsergroup(?UserGroup $usergroup): self
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    public function getComponent(): ?Component
    {
        return $this->component;
    }

    public function setComponent(?Component $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
}
