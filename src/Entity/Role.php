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
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 * @ORM\Table(name="se_role")
 * 
 * @UniqueEntity("name",
 *     message="Already exist."
 * )
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class Role
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
     *     message = "Can not be blank"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Assert\NotBlank(
     *     message = "Can not be blank"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *     message = "Can not be blank"
     * )
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $titleEn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titleFr;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Component")
     */
    private $component;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupComponentRole", mappedBy="role")
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

    public function getTitle(): ? string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRoute(): ? string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

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

    public function getPosition(): ? int
    {
        return $this->position;
    }

    public function setPosition(? int $position): self
    {
        $this->position = $position;

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

    public function getTitleEn(): ? string
    {
        return $this->titleEn;
    }

    public function setTitleEn(? string $titleEn): self
    {
        $this->titleEn = $titleEn;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMenu(): ?self
    {
        return $this->menu;
    }

    public function setMenu(?self $menu): self
    {
        $this->menu = $menu;

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
	
	/**
     * Generates the magic method
     * 
     */
    public function __toString()
    {
        // to show the name of the Role in the select
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
            $groupComponentRole->setRole($this);
        }

        return $this;
    }

    public function removeGroupComponentRole(GroupComponentRole $groupComponentRole): self
    {
        if ($this->groupComponentRoles->contains($groupComponentRole)) {
            $this->groupComponentRoles->removeElement($groupComponentRole);
            // set the owning side to null (unless already changed)
            if ($groupComponentRole->getRole() === $this) {
                $groupComponentRole->setRole(null);
            }
        }

        return $this;
    }
}
