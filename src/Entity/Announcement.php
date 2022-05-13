<?php

namespace App\Entity;

use App\Repository\AnnouncementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["announce:list"])]
    private $id;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:150, minMessage:"Le titre doit contenir au minimun {{ limit }} caractères", maxMessage:"Le titre ne doit pas dépasser {{ limit }} caractères")]
    #[Groups(["announce:list"])]
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
    
    #[ORM\ManyToOne(targetEntity: SubCategory::class, inversedBy: 'announcements')]
    #[Assert\NotBlank]
    private $subCategory;

    #[ORM\Column(type: 'float', scale:4, precision:6)]
    private $lat;

    #[ORM\Column(type: 'float', scale:4, precision:7)]
    private $lng;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $department;

    #[ORM\Column(type: 'string', length: 5)]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 255)]
    private $region;

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Picture::class, orphanRemoval:true, cascade: ['persist', 'remove'])]
    private $pictures;

    /**
     * @Assert\All({
     *   @Assert\Image(mimeTypes="image/jpeg")
     * })
     */
    private $pictureFiles;

    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'favorites')]
    private $favorites;

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Message::class)]
    private $message;

    #[ORM\Column(type: 'boolean')]
    private $isOnline = false;

    #[ORM\ManyToMany(targetEntity: SubCategory::class, inversedBy: 'announcementPropose', cascade: ['persist', 'remove'])]
    private $propose;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->message = new ArrayCollection();
        $this->propose = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
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

    public function setDescription(?string $description): self
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

    public function getSubCategory(): ?SubCategory
    {
        return $this->subCategory;
    }

    public function setSubCategory(?SubCategory $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAnnouncement($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAnnouncement() === $this) {
                $picture->setAnnouncement(null);
            }
        }

        return $this;
    }


    /**
     * Get })
     */ 
    public function getPictureFiles()
    {
        return $this->pictureFiles;
    }

    /**
     * Set })
     *
     * @return  self
     */ 
    public function setPictureFiles($pictureFiles)
    {
        foreach($pictureFiles as $pictureFile) {
            $picture = new Picture();
            $picture->setImageFile($pictureFile);
            $this->addPicture($picture);
        }
        $this->pictureFiles = $pictureFiles;

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Profile $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Profile $favorite): self
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->message->contains($message)) {
            $this->message[] = $message;
            $message->setAnnouncement($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->message->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAnnouncement() === $this) {
                $message->setAnnouncement(null);
            }
        }

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    /**
     * @return Collection<int, SubCategory>
     */
    public function getPropose(): Collection
    {
        return $this->propose;
    }

    public function addPropose(SubCategory $propose): self
    {
        if (!$this->propose->contains($propose)) {
            $this->propose[] = $propose;
        }

        return $this;
    }

    public function removePropose(SubCategory $propose): self
    {
        $this->propose->removeElement($propose);

        return $this;
    }

}
