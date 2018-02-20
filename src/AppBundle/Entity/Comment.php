<?php

namespace AppBundle\Entity;

use FOS\CommentBundle\Entity\Comment as BaseComment;

/**
 * Comment
 */
class Comment extends BaseComment
{
    /**
     * @var int
     */
    protected $id;

}
