<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostCommentReplyRepository")
 */
class PostCommentReply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PostComment", inversedBy="postCommentReplies")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $postComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="postCommentReplies")
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

    public function getPostComment(): ?PostComment
    {
        return $this->postComment;
    }

    public function setPostComment(?PostComment $postComment): self
    {
        $this->postComment = $postComment;

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
