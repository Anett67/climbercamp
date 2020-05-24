<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 * @UniqueEntity(
 * fields={"email"},
 * message="Un utilisateur avec cet email existe déjà"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=6, minMessage="Veuillez renseigner un mot de passe avec au moins 6 caractères ")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne sont pas équivalents")
     */

    private $confirmPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="image")
     */

    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="postedBy")
     */
    private $posts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="users")
     */
    private $ville;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClimbingClub", inversedBy="users")
     */
    private $climbingClub;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClimbingCategorie", inversedBy="users")
     */
    private $climbingCategorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Level", inversedBy="users", cascade={"persist"})
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="postedBy")
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="interestedUsers")
     */
    private $savedEvents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostLike", mappedBy="postedBy")
     */
    private $postLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventLike", mappedBy="postedBy")
     */
    private $eventLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="fromUser")
     */
    private $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="toUser")
     */
    private $receivedMessages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostComment", mappedBy="postedBy")
     */
    private $postComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventComment", mappedBy="postedBy")
     */
    private $eventComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventCommentLike", mappedBy="postedBy")
     */
    private $eventCommentLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventCommentReply", mappedBy="postedBy")
     */
    private $eventCommentReplies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostCommentLike", mappedBy="postedBy")
     */
    private $postCommentLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostCommentReply", mappedBy="postedBy")
     */
    private $postCommentReplies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->climbingClub = new ArrayCollection();
        $this->climbingCategorie = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->savedEvents = new ArrayCollection();
        $this->postLikes = new ArrayCollection();
        $this->eventLikes = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->postComments = new ArrayCollection();
        $this->eventComments = new ArrayCollection();
        $this->eventCommentLikes = new ArrayCollection();
        $this->eventCommentReplies = new ArrayCollection();
        $this->postCommentLikes = new ArrayCollection();
        $this->postCommentReplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getRoles()
    {
        return [$this->roles];
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setPostedBy($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getPostedBy() === $this) {
                $post->setPostedBy(null);
            }
        }

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
     * @return Collection|ClimbingClub[]
     */
    public function getClimbingClub(): Collection
    {
        return $this->climbingClub;
    }

    public function addClimbingClub(ClimbingClub $climbingClub): self
    {
        if (!$this->climbingClub->contains($climbingClub)) {
            $this->climbingClub[] = $climbingClub;
        }

        return $this;
    }

    public function removeClimbingClub(ClimbingClub $climbingClub): self
    {
        if ($this->climbingClub->contains($climbingClub)) {
            $this->climbingClub->removeElement($climbingClub);
        }

        return $this;
    }

    /**
     * @return Collection|climbingCategorie[]
     */
    public function getClimbingCategorie(): Collection
    {
        return $this->climbingCategorie;
    }

    public function addClimbingCategorie(climbingCategorie $climbingCategorie): self
    {
        if (!$this->climbingCategorie->contains($climbingCategorie)) {
            $this->climbingCategorie[] = $climbingCategorie;
        }

        return $this;
    }

    public function removeClimbingCategorie(climbingCategorie $climbingCategorie): self
    {
        if ($this->climbingCategorie->contains($climbingCategorie)) {
            $this->climbingCategorie->removeElement($climbingCategorie);
        }

        return $this;
    }

    public function getLevel(): ?level
    {
        return $this->level;
    }

    public function setLevel(?level $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setPostedBy($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getPostedBy() === $this) {
                $event->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getSavedEvents(): Collection
    {
        return $this->savedEvents;
    }

    public function addSavedEvent(Event $savedEvent): self
    {
        if (!$this->savedEvents->contains($savedEvent)) {
            $this->savedEvents[] = $savedEvent;
            $savedEvent->addInterestedUser($this);
        }

        return $this;
    }

    public function removeSavedEvent(Event $savedEvent): self
    {
        if ($this->savedEvents->contains($savedEvent)) {
            $this->savedEvents->removeElement($savedEvent);
            $savedEvent->removeInterestedUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|PostLike[]
     */
    public function getPostLikes(): Collection
    {
        return $this->postLikes;
    }

    public function addPostLike(PostLike $postLike): self
    {
        if (!$this->postLikes->contains($postLike)) {
            $this->postLikes[] = $postLike;
            $postLike->setPostedBy($this);
        }

        return $this;
    }

    public function removePostLike(PostLike $postLike): self
    {
        if ($this->postLikes->contains($postLike)) {
            $this->postLikes->removeElement($postLike);
            // set the owning side to null (unless already changed)
            if ($postLike->getPostedBy() === $this) {
                $postLike->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventLike[]
     */
    public function getEventLikes(): Collection
    {
        return $this->eventLikes;
    }

    public function addEventLike(EventLike $eventLike): self
    {
        if (!$this->eventLikes->contains($eventLike)) {
            $this->eventLikes[] = $eventLike;
            $eventLike->setPostedBy($this);
        }

        return $this;
    }

    public function removeEventLike(EventLike $eventLike): self
    {
        if ($this->eventLikes->contains($eventLike)) {
            $this->eventLikes->removeElement($eventLike);
            // set the owning side to null (unless already changed)
            if ($eventLike->getPostedBy() === $this) {
                $eventLike->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages[] = $sentMessage;
            $sentMessage->setFromUser($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sentMessages->contains($sentMessage)) {
            $this->sentMessages->removeElement($sentMessage);
            // set the owning side to null (unless already changed)
            if ($sentMessage->getFromUser() === $this) {
                $sentMessage->setFromUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages[] = $receivedMessage;
            $receivedMessage->setToUser($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        if ($this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages->removeElement($receivedMessage);
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getToUser() === $this) {
                $receivedMessage->setToUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostComment[]
     */
    public function getPostComments(): Collection
    {
        return $this->postComments;
    }

    public function addPostComment(PostComment $postComment): self
    {
        if (!$this->postComments->contains($postComment)) {
            $this->postComments[] = $postComment;
            $postComment->setPostedBy($this);
        }

        return $this;
    }

    public function removePostComment(PostComment $postComment): self
    {
        if ($this->postComments->contains($postComment)) {
            $this->postComments->removeElement($postComment);
            // set the owning side to null (unless already changed)
            if ($postComment->getPostedBy() === $this) {
                $postComment->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventComment[]
     */
    public function getEventComments(): Collection
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComment $eventComment): self
    {
        if (!$this->eventComments->contains($eventComment)) {
            $this->eventComments[] = $eventComment;
            $eventComment->setPostedBy($this);
        }

        return $this;
    }

    public function removeEventComment(EventComment $eventComment): self
    {
        if ($this->eventComments->contains($eventComment)) {
            $this->eventComments->removeElement($eventComment);
            // set the owning side to null (unless already changed)
            if ($eventComment->getPostedBy() === $this) {
                $eventComment->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventCommentLike[]
     */
    public function getEventCommentLikes(): Collection
    {
        return $this->eventCommentLikes;
    }

    public function addEventCommentLike(EventCommentLike $eventCommentLike): self
    {
        if (!$this->eventCommentLikes->contains($eventCommentLike)) {
            $this->eventCommentLikes[] = $eventCommentLike;
            $eventCommentLike->setPostedBy($this);
        }

        return $this;
    }

    public function removeEventCommentLike(EventCommentLike $eventCommentLike): self
    {
        if ($this->eventCommentLikes->contains($eventCommentLike)) {
            $this->eventCommentLikes->removeElement($eventCommentLike);
            // set the owning side to null (unless already changed)
            if ($eventCommentLike->getPostedBy() === $this) {
                $eventCommentLike->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventCommentReply[]
     */
    public function getEventCommentReplies(): Collection
    {
        return $this->eventCommentReplies;
    }

    public function addEventCommentReply(EventCommentReply $eventCommentReply): self
    {
        if (!$this->eventCommentReplies->contains($eventCommentReply)) {
            $this->eventCommentReplies[] = $eventCommentReply;
            $eventCommentReply->setPostedBy($this);
        }

        return $this;
    }

    public function removeEventCommentReply(EventCommentReply $eventCommentReply): self
    {
        if ($this->eventCommentReplies->contains($eventCommentReply)) {
            $this->eventCommentReplies->removeElement($eventCommentReply);
            // set the owning side to null (unless already changed)
            if ($eventCommentReply->getPostedBy() === $this) {
                $eventCommentReply->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostCommentLike[]
     */
    public function getPostCommentLikes(): Collection
    {
        return $this->postCommentLikes;
    }

    public function addPostCommentLike(PostCommentLike $postCommentLike): self
    {
        if (!$this->postCommentLikes->contains($postCommentLike)) {
            $this->postCommentLikes[] = $postCommentLike;
            $postCommentLike->setPostedBy($this);
        }

        return $this;
    }

    public function removePostCommentLike(PostCommentLike $postCommentLike): self
    {
        if ($this->postCommentLikes->contains($postCommentLike)) {
            $this->postCommentLikes->removeElement($postCommentLike);
            // set the owning side to null (unless already changed)
            if ($postCommentLike->getPostedBy() === $this) {
                $postCommentLike->setPostedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostCommentReply[]
     */
    public function getPostCommentReplies(): Collection
    {
        return $this->postCommentReplies;
    }

    public function addPostCommentReply(PostCommentReply $postCommentReply): self
    {
        if (!$this->postCommentReplies->contains($postCommentReply)) {
            $this->postCommentReplies[] = $postCommentReply;
            $postCommentReply->setPostedBy($this);
        }

        return $this;
    }

    public function removePostCommentReply(PostCommentReply $postCommentReply): self
    {
        if ($this->postCommentReplies->contains($postCommentReply)) {
            $this->postCommentReplies->removeElement($postCommentReply);
            // set the owning side to null (unless already changed)
            if ($postCommentReply->getPostedBy() === $this) {
                $postCommentReply->setPostedBy(null);
            }
        }

        return $this;
    }

    public function getSalt()
    {
        
    }

    public function eraseCredentials()
    {
        
    }

    public function getUsername()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

}
