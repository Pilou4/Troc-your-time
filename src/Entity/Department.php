<?php

namespace App\Entity;

use App\Entity\Announcement;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Le nom du département doit être unqiue')]
#[UniqueEntity(fields: ['number'], message: 'Le numéro de département doit être unqiue')]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Length(min:1, max:2, maxMessage:"Cette valeur est trop longue (maximum {{ limit }} caractères)")]
    private $number;

    #[ORM\Column(type: 'string', length: 60)]
    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:60, minMessage:"Le nom du département doit contenir au minimun {{ limit }} caractères", maxMessage:"Le nom du département ne doit pas dépasser {{ limit }} caractères")]
    private $name;
    
    private $fullName;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'departments')]
    private $region;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Announcement::class)]
    private $announcements;

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

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
            $announcement->setDepartment($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): self
    {
        if ($this->announcements->removeElement($announcement)) {
            // set the owning side to null (unless already changed)
            if ($announcement->getDepartment() === $this) {
                $announcement->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of fullName
     */ 
    public function getFullName()
    {
        return $this->getNumber() . '-' . $this->getName();
    }
}
