<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostCommentLikeRepository")
 * @UniqueEntity(
 * fields={"postComment" = "postedBy"},
* )
 */
class PostCommentLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PostComment", inversedBy="postCommentLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="postCommentLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedBy;

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
}
