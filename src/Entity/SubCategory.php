<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SubCategoryRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubCategoryRepository::class)]
class SubCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["sub-category:search"])]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:100, minMessage:"Le nom d'une sous catégorie doit contenir au minimun {{ limit }} caractères", maxMessage:"Le nom d'une sous catégorie ne peux pas dépasser {{ limit }} caractères")]
    #[Groups(["sub-category:search"])]
    private $name;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'subCategories')]
    private $category;

    #[ORM\OneToMany(mappedBy: 'subCategory', targetEntity: Announcement::class)]
    private $announcements;

    #[ORM\ManyToMany(targetEntity: Profile::class, mappedBy: 'propose')]
    private $profilePropose;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'research')]
    private $profileResearch;

    #[ORM\ManyToMany(targetEntity: Announcement::class, mappedBy: 'propose')]
    private $announcementPropose;
    
    public function __construct()
    {
        $this->announcements = new ArrayCollection();
        $this->profilePropose = new ArrayCollection();
        $this->profileResearch = new ArrayCollection();
        $this->announcementPropose = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $announcement->setSubCategory($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getSubCategory() === $this) {
                $announcement->setSubCategory(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getProfilePropose(): Collection
    {
        return $this->profilePropose;
    }

    public function addProfilePropose(Profile $profilePropose): self
    {
        if (!$this->profilePropose->contains($profilePropose)) {
            $this->profilePropose[] = $profilePropose;
            $profilePropose->addPropose($this);
        }

        return $this;
    }

    public function removeProfilePropose(Profile $profilePropose): self
    {
        if ($this->profilePropose->removeElement($profilePropose)) {
            $profilePropose->removePropose($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getProfileResearch(): Collection
    {
        return $this->profileResearch;
    }

    public function addProfileResearch(Profile $profileResearch): self
    {
        if (!$this->profileResearch->contains($profileResearch)) {
            $this->profileResearch[] = $profileResearch;
        }

        return $this;
    }

    public function removeProfileResearch(Profile $profileResearch): self
    {
        $this->profileResearch->removeElement($profileResearch);

        return $this;
    }

    /**
     * @return Collection<int, Announcement>
     */
    public function getAnnouncementPropose(): Collection
    {
        return $this->announcementPropose;
    }

    public function addAnnouncementPropose(Announcement $announcementPropose): self
    {
        if (!$this->announcementPropose->contains($announcementPropose)) {
            $this->announcementPropose[] = $announcementPropose;
            $announcementPropose->addPropose($this);
        }

        return $this;
    }

    public function removeAnnouncementPropose(Announcement $announcementPropose): self
    {
        if ($this->announcementPropose->removeElement($announcementPropose)) {
            $announcementPropose->removePropose($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

}
