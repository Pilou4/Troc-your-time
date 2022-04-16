<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    // #[Assert\NotBlank]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $message;

    #[ORM\Column(type: 'datetime_immutable')]
    /**
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    #[ORM\Column(type: 'boolean')]
    private $is_read = 0;

    #[ORM\ManyToOne(targetEntity: Profile::class, inversedBy: 'sent')]
    #[ORM\JoinColumn(nullable: false)]
    private $sender;

    #[ORM\ManyToOne(targetEntity: Profile::class, inversedBy: 'received')]
    #[ORM\JoinColumn(nullable: false)]
    private $recipient;

    #[ORM\Column(type: 'boolean')]
    private $isBasket = 0;

    #[ORM\ManyToOne(targetEntity: Announcement::class, inversedBy: 'message')]
    private $announcement;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getSender(): ?Profile
    {
        return $this->sender;
    }

    public function setSender(?Profile $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?Profile
    {
        return $this->recipient;
    }

    public function setRecipient(?Profile $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getIsBasket(): ?bool
    {
        return $this->isBasket;
    }

    public function setIsBasket(bool $isBasket): self
    {
        $this->isBasket = $isBasket;

        return $this;
    }

    public function getAnnouncement(): ?Announcement
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcement $announcement): self
    {
        $this->announcement = $announcement;

        return $this;
    }
}
