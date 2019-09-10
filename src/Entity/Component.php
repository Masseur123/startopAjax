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
 * @ORM\Entity(repositoryClass="App\Repository\ComponentRepository")
 * @ORM\Table(name="se_component")
 * @UniqueEntity("name",
 *     message="Already exist."
 * )
 * 
 * @UniqueEntity("route",
 *     message="Already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Component
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
     *     message = "Name's can not be blank"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_enabled;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=21)
     * 
     * @Assert\NotBlank(
     *     message = "Not blank"
     * )
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank(
     *     message = "Not blank"
     * )
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *     message = "Not blank"
     * )
     */
    private $nameEn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameFr;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupComponentRole", mappedBy="component")
     */
    private $groupComponentRoles;

    public function __construct()
    {
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

    public function getIsEnabled(): ? bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function getPosition(): ? int
    {
        return $this->position;
    }

    public function setPosition(? int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getIcon(): ? string
    {
        return $this->icon;
    }

    public function setIcon(? string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getRoute(): ? string
    {
        return $this->route;
    }

    public function setRoute(? string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getNameEn(): ? string
    {
        return $this->nameEn;
    }

    public function setNameEn(? string $nameEn): self
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    public function getNameFr(): ? string
    {
        return $this->nameFr;
    }

    public function setNameFr(? string $nameFr): self
    {
        $this->nameFr = $nameFr;

        return $this;
    }

    public function __toString()
    {
        // to show the fullname of the User in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
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
            $groupComponentRole->setComponent($this);
        }

        return $this;
    }

    public function removeGroupComponentRole(GroupComponentRole $groupComponentRole): self
    {
        if ($this->groupComponentRoles->contains($groupComponentRole)) {
            $this->groupComponentRoles->removeElement($groupComponentRole);
            // set the owning side to null (unless already changed)
            if ($groupComponentRole->getComponent() === $this) {
                $groupComponentRole->setComponent(null);
            }
        }

        return $this;
    }
}
