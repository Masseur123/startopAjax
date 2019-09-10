<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Housing")
     * @ORM\JoinColumn(nullable=false)
     */
    private $roomtax;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     * @ORM\JoinColumn(nullable=false)
     */
    private $citytax;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRoomtax(): ?Housing
    {
        return $this->roomtax;
    }

    public function setRoomtax(?Housing $roomtax): self
    {
        $this->roomtax = $roomtax;

        return $this;
    }

    public function getCitytax(): ?City
    {
        return $this->citytax;
    }

    public function setCitytax(?City $citytax): self
    {
        $this->citytax = $citytax;

        return $this;
    }
}
