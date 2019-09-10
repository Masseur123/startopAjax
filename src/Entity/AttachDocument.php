<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttachDocumentRepository")
 * @ORM\Table(name="tra_attach_document")
 *
 * @author Jiogue Tadie Hervé Marcel <fastochehost@gmail.com>
 */
class AttachDocument
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transit")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentFile")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF, DOC, DOCX,  EXCEL, RAR or ZIP file.")
     * @Assert\File(mimeTypes={ "application/pdf", "application/msword", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "image/jpeg", "application/x-rar-compressed", "application/zip" })
     *
     */
    private $fileupload;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransit(): ?Transit
    {
        return $this->transit;
    }

    public function setTransit(?Transit $transit): self
    {
        $this->transit = $transit;

        return $this;
    }

    public function getDocument(): ?DocumentFile
    {
        return $this->document;
    }

    public function setDocument(?DocumentFile $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getFileupload(): ?string
    {
        return $this->fileupload;
    }

    public function setFileupload(string $fileupload): self
    {
        $this->fileupload = $fileupload;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
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

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }
}
