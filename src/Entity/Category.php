<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
/**
 * @Vich\Uploadable()
 */
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Announcement::class)]
    private $announcements;

    #[ORM\Column(type: 'datetime_immutable')]
    /**
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    /**
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $filename;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes="image/jpeg"
     * )
     * @Vich\UploadableField(mapping="category_picture", fileNameProperty="filename")
     */
    private $imageFile;

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Announcement>
     */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): self
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements[] = $announcement;
            $announcement->setCategory($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getCategory() === $this) {
                $announcement->setCategory(null);
            }
        }

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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }

    /**
     * Get mimeTypes="image/jpeg"
     *
     * @return  File|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set mimeTypes="image/jpeg"
     *
     * @param  File|null  $imageFile  mimeTypes="image/jpeg"
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}
