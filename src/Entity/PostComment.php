<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostCommentRepository")
 */
class PostComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="postComments")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="postComments")
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostCommentLike", mappedBy="postComment")
     */
    private $postCommentLikes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostCommentReply", mappedBy="postComment")
     */
    private $postCommentReplies;

    public function __construct()
    {
        $this->postCommentLikes = new ArrayCollection();
        $this->postCommentReplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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
            $postCommentLike->setPostComment($this);
        }

        return $this;
    }

    public function removePostCommentLike(PostCommentLike $postCommentLike): self
    {
        if ($this->postCommentLikes->contains($postCommentLike)) {
            $this->postCommentLikes->removeElement($postCommentLike);
            // set the owning side to null (unless already changed)
            if ($postCommentLike->getPostComment() === $this) {
                $postCommentLike->setPostComment(null);
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
            $postCommentReply->setPostComment($this);
        }

        return $this;
    }

    public function removePostCommentReply(PostCommentReply $postCommentReply): self
    {
        if ($this->postCommentReplies->contains($postCommentReply)) {
            $this->postCommentReplies->removeElement($postCommentReply);
            // set the owning side to null (unless already changed)
            if ($postCommentReply->getPostComment() === $this) {
                $postCommentReply->setPostComment(null);
            }
        }

        return $this;
    }
}
