<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventCommentReplyRepository")
 */
class EventCommentReply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EventComment", inversedBy="eventCommentReplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eventComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="eventCommentReplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postedAt;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventComment(): ?EventComment
    {
        return $this->eventComment;
    }

    public function setEventComment(?EventComment $eventComment): self
    {
        $this->eventComment = $eventComment;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->postedBy;
    }

    public function setPostedBy(?User $postedBy): self
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeInterface $postedAt): self
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}
