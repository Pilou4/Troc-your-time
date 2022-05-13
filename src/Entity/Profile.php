<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfileRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[UniqueEntity(fields: ['username'], message: "Nom d'utilisateur pas disponible")]
/**
 * @Vich\Uploadable()
 */
class Profile 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $gender;

    #[ORM\Column(type: 'string', length: 40)]
    #[Assert\Length(min:3, max:40, minMessage:"Le prénom doit contenir au minimun {{ limit }} caractères", maxMessage:"Le prénom ne peut pas dépasser {{ limit }} caractères")]
    #[Assert\Regex("/^[A-Za-z0-9]+[^\s!?\/.*#|]+(?:[A-Za-z0-9]+)*$/")] // [^\s!?\/.*#|]
    private $username;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min:2, max:100, minMessage:"Le prénom doit contenir au minimun {{ limit }} caractères", maxMessage:"Le prénom ne peut pas dépasser {{ limit }} caractères")]
    private $firstname;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min:2, max:100, minMessage:"Le nom doit contenir au minimun {{ limit }} caractères", maxMessage:"Le nom ne peut pas dépasser {{ limit }} caractères")]
    private $lastname;

    #[ORM\Column(type: 'date', nullable: true)]
    private $birthday;

    #[ORM\Column(type: 'integer')]
    private $number;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $street;

    #[ORM\Column(type: 'string', length: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex("/^[0-9]{5}$/")]
    #[Assert\Length(min:5, max:5, minMessage:"Le code postal doit contenir {{ limit }} caractères", maxMessage:"Le code postal doit contenir {{ limit }} caractères")]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $department;

    #[ORM\Column(type: 'string', length: 255)]
    private $region;

    #[ORM\Column(type: 'float', scale:4, precision:6)]
    private $lat;

    #[ORM\Column(type: 'float', scale:4, precision:7)]
    private $lng;

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
     * @Vich\UploadableField(mapping="profile_picture", fileNameProperty="filename")
     */
    private $imageFile;

    #[ORM\OneToOne(mappedBy: 'profile', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $user;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Message::class, orphanRemoval: true)]
    private $sent;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Message::class, orphanRemoval: true)]
    private $received;

    #[ORM\OneToMany(mappedBy: 'profile', targetEntity: Announcement::class)]
    private $announcements;

    #[ORM\ManyToMany(targetEntity: Announcement::class, mappedBy: 'favorites')]
    private $favorites;

    #[ORM\ManyToMany(targetEntity: SubCategory::class, inversedBy: 'profilePropose')]
    private $propose;

    #[ORM\ManyToMany(targetEntity: SubCategory::class, mappedBy: 'profileResearch')]
    private $research;

    public function __construct()
    {
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->propose = new ArrayCollection();
        $this->research = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setProfile(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getProfile() !== $this) {
            $user->setProfile($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of street
     */ 
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of street
     *
     * @return  self
     */ 
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(Message $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setSender($this);
        }

        return $this;
    }

    public function removeSent(Message $sent): self
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getSender() === $this) {
                $sent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(Message $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received[] = $received;
            $received->setRecipient($this);
        }

        return $this;
    }

    public function removeReceived(Message $received): self
    {
        if ($this->received->removeElement($received)) {
            // set the owning side to null (unless already changed)
            if ($received->getRecipient() === $this) {
                $received->setRecipient(null);
            }
        }

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
            $announcement->setProfile($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getProfile() === $this) {
                $announcement->setProfile(null);
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

    /**
     * Get the value of filename
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;
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
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }

    /**
     * @return Collection<int, Announcement>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Announcement $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->addFavorite($this);
        }

        return $this;
    }

    public function removeFavorite(Announcement $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            $favorite->removeFavorite($this);
        }

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get the value of lat
     */ 
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set the value of lat
     *
     * @return  self
     */ 
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get the value of lng
     */ 
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set the value of lng
     *
     * @return  self
     */ 
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    /**
     * @return Collection<int, SubCategory>
     */
    public function getResearch(): Collection
    {
        return $this->research;
    }

    public function addResearch(SubCategory $research): self
    {
        if (!$this->research->contains($research)) {
            $this->research[] = $research;
            $research->addProfileResearch($this);
        }

        return $this;
    }

    public function removeResearch(SubCategory $research): self
    {
        if ($this->research->removeElement($research)) {
            $research->removeProfileResearch($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username;
    }


}
