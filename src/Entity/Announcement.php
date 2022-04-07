<?php

namespace App\Entity;

use App\Repository\AnnouncementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:150, minMessage:"Le titre doit contenir au minimun {{ limit }} caractères", maxMessage:"Le titre ne doit pas dépasser {{ limit }} caractères")]
    private $title;

    #[ORM\Column(type: 'string', length: 180)]
    #[Gedmo\Slug(fields: ["title"])]
    private $slug;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: "create")]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: "update")]
    private $updatedAt;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $description;

    #[ORM\ManyToOne(targetEntity: Profile::class, inversedBy: 'announcements')]
    private $profile;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'announcements')]
    private $department;

    #[ORM\ManyToOne(targetEntity: SubCategory::class, inversedBy: 'announcements')]
    private $subCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getSubCategory(): ?SubCategory
    {
        return $this->subCategory;
    }

    public function setSubCategory(?SubCategory $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

}
