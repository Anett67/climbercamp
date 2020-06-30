<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClimbingClubRepository")
 * @Vich\Uploadable
 */
class ClimbingClub
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="climbingClubs")
     */
    private $ville;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="climbingClub")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="club_image", fileNameProperty="image")
     */

    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addresse;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClimbingCategorie", inversedBy="climbingClubs")
     */
    private $climbingCategories;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->climbingCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addClimbingClub($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeClimbingClub($this);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;

        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    public function setUpdatedAt(\DateTimeInterface $createdAt)
    {
        $this->updatedAt = $createdAt;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(?string $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }

    /**
     * @return Collection|ClimbingCategorie[]
     */
    public function getClimbingCategories(): Collection
    {
        return $this->climbingCategories;
    }

    public function addClimbingCategory(ClimbingCategorie $climbingCategory): self
    {
        if (!$this->climbingCategories->contains($climbingCategory)) {
            $this->climbingCategories[] = $climbingCategory;
        }

        return $this;
    }

    public function removeClimbingCategory(ClimbingCategorie $climbingCategory): self
    {
        if ($this->climbingCategories->contains($climbingCategory)) {
            $this->climbingCategories->removeElement($climbingCategory);
        }

        return $this;
    }
}
